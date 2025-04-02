<?php

namespace App\Controllers;

use App\Models\AngsuranKavlingModel;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Csv;

class AngsuranKavling extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new AngsuranKavlingModel();
    }

    public function index()
    {
        $lokasi = $this->request->getGet('lokasi');
        // $data['locations'] = $this->model->getLocations();
        $data['locations'] = [
            [
                'lokasi' => 'Desa Banjaran, Driyorejo'
            ],
            [
                'lokasi' => 'Menganti KPN, Menganti'
            ]
        ];
        // echo json_encode($data['locations']); die(); //[{"lokasi":"Desa Banjaran, Driyorejo"}]
        // echo $data['locations'][0]['lokasi']; die();
        
        if ($lokasi) {
            $data['angsuran'] = $this->model->getByLocation($lokasi);
            $data['total_angsuran'] = $this->model->getSummaryByLocation($lokasi);
            $data['selected_location'] = $lokasi;
        } else {
            // If no location selected, show the first location's data
            if (!empty($data['locations'])) {
                $firstLoc = $data['locations'][0]['lokasi'];
                $data['angsuran'] = $this->model->getByLocation($firstLoc);
                $data['total_angsuran'] = $this->model->getSummaryByLocation($firstLoc);
                $data['selected_location'] = $firstLoc;
            } else {
                $data['angsuran'] = [];
                $data['total_angsuran'] = 0;
                $data['selected_location'] = '';
            }
        }
        
        return view('angsuran_kavling', $data);
    }

    public function create()
    {
        $data = [
            'lokasi' => $this->request->getPost('lokasi'),
            'tanggal' => $this->request->getPost('tanggal'),
            'jumlah' => $this->request->getPost('jumlah'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->model->insert($data);
        return redirect()->to('/angsuran-kavling?lokasi=' . urlencode($data['lokasi']))
                        ->with('success', 'Data berhasil ditambahkan');
    }

    public function update($id)
    {
        $data = [
            'lokasi' => $this->request->getPost('lokasi'),
            'tanggal' => $this->request->getPost('tanggal'),
            'jumlah' => $this->request->getPost('jumlah'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->model->update($id, $data);
        return redirect()->to('/angsuran-kavling?lokasi=' . urlencode($data['lokasi']))
                        ->with('success', 'Data berhasil diupdate');
    }

    public function delete($id)
    {
        $record = $this->model->find($id);
        $lokasi = $record['lokasi'];
        
        $this->model->delete($id);
        return redirect()->to('/angsuran-kavling?lokasi=' . urlencode($lokasi))
                        ->with('success', 'Data berhasil dihapus');
    }

    public function import()
    {
        $file = $this->request->getFile('excel_file');
        $startRow = $this->request->getPost('start_row') ?? 6;
        $endRow = $this->request->getPost('end_row') ?? 46;
        $importType = $this->request->getPost('import_type') ?? 'excel';
        $lokasi = $this->request->getPost('lokasi');
        
        if (empty($lokasi)) {
            return redirect()->to('/angsuran-kavling')
                ->with('error', 'Lokasi harus dipilih untuk import data');
        }
        
        if ($file->isValid() && !$file->hasMoved()) {
            try {
                if ($importType === 'excel') {
                    return $this->importExcel($file, $startRow, $endRow, $lokasi);
                } else {
                    return $this->importCsv($file, $startRow, $endRow, $lokasi);
                }
            } catch (\Exception $e) {
                return redirect()->to('/angsuran-kavling?lokasi=' . urlencode($lokasi))
                    ->with('error', 'Gagal mengimport file: ' . $e->getMessage());
            }
        }
        
        return redirect()->to('/angsuran-kavling?lokasi=' . urlencode($lokasi))
            ->with('error', 'File tidak valid');
    }

    private function convertDate($dateString)
    {
        // Remove any leading/trailing whitespace
        $dateString = trim($dateString);
        
        // Try to parse the date using different formats
        $formats = [
            'd/m/Y',  // 31/12/2024
            'd/m/y',  // 31/12/24
            'd-m-Y',  // 31-12-2024
            'd-m-y',  // 31-12-24
            'Y-m-d',  // Already in correct format
        ];
        
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $dateString);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }
        
        // If no format matches, throw an exception
        throw new \Exception("Format tanggal tidak valid: {$dateString}. Gunakan format dd/mm/yyyy atau dd/mm/yy");
    }

    private function importExcel($file, $startRow, $endRow, $lokasi)
    {
        $excelReader = new Xlsx();
        
        // Move file to writable directory
        $file->move(WRITEPATH . 'uploads');
        
        try {
            // Read the Excel file
            $spreadsheet = $excelReader->load(WRITEPATH . 'uploads/' . $file->getName());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            // Validate row numbers
            if ($startRow < 1 || $endRow < $startRow || $endRow > count($rows)) {
                throw new \Exception('Baris awal atau baris akhir tidak valid');
            }
            
            // Process only the specified rows
            $successCount = 0;
            $errors = [];
            
            for ($i = $startRow - 1; $i < $endRow; $i++) {
                if (isset($rows[$i]) && !empty($rows[$i][0])) {
                    try {
                        $data = [
                            'lokasi' => $lokasi,
                            'tanggal' => $this->convertDate($rows[$i][1]), // Column B: Tanggal
                            'jumlah' => $rows[$i][3], // Column D: Jumlah
                            'keterangan' => $rows[$i][2] ?? '', // Column C: Keterangan (optional)
                        ];
                        
                        if ($this->model->insert($data)) {
                            $successCount++;
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Baris " . ($i + 1) . ": " . $e->getMessage();
                    }
                }
            }
            
            // Delete the uploaded file
            unlink(WRITEPATH . 'uploads/' . $file->getName());
            
            if (!empty($errors)) {
                return redirect()->to('/angsuran-kavling?lokasi=' . urlencode($lokasi))
                    ->with('error', "Berhasil mengimport {$successCount} data, dengan " . count($errors) . " error:<br>" . implode("<br>", $errors));
            }
            
            return redirect()->to('/angsuran-kavling?lokasi=' . urlencode($lokasi))
                ->with('success', "Berhasil mengimport {$successCount} data dari Excel");
                
        } catch (\Exception $e) {
            // Delete the uploaded file if exists
            if (file_exists(WRITEPATH . 'uploads/' . $file->getName())) {
                unlink(WRITEPATH . 'uploads/' . $file->getName());
            }
            throw $e;
        }
    }

    private function importCsv($file, $startRow, $endRow, $lokasi)
    {
        // Move file to writable directory
        $file->move(WRITEPATH . 'uploads');
        $filepath = WRITEPATH . 'uploads/' . $file->getName();
        
        try {
            // Open CSV file
            $handle = fopen($filepath, "r");
            if ($handle === FALSE) {
                throw new \Exception('Tidak dapat membuka file CSV');
            }
            
            $row = 1;
            $successCount = 0;
            $errors = [];
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Skip rows before start row
                if ($row < $startRow) {
                    $row++;
                    continue;
                }
                
                // Stop if we've reached the end row
                if ($row > $endRow) {
                    break;
                }
                
                // Process row if it has data
                if (!empty($data[0])) {
                    try {
                        $insertData = [
                            'lokasi' => $lokasi,
                            'tanggal' => $this->convertDate($data[1]), // Column 2: Tanggal
                            'jumlah' => str_replace(['Rp', '.', ','], '', $data[3]), // Column 4: Jumlah
                            'keterangan' => $data[2] ?? '', // Column 3: Keterangan (optional)
                        ];
                        
                        if ($this->model->insert($insertData)) {
                            $successCount++;
                        }
                    } catch (\Exception $e) {
                        $errors[] = "Baris {$row}: " . $e->getMessage();
                    }
                }
                
                $row++;
            }
            
            fclose($handle);
            unlink($filepath);
            
            if (!empty($errors)) {
                return redirect()->to('/angsuran-kavling?lokasi=' . urlencode($lokasi))
                    ->with('error', "Berhasil mengimport {$successCount} data, dengan " . count($errors) . " error:<br>" . implode("<br>", $errors));
            }
            
            return redirect()->to('/angsuran-kavling?lokasi=' . urlencode($lokasi))
                ->with('success', "Berhasil mengimport {$successCount} data dari CSV");
                
        } catch (\Exception $e) {
            // Clean up
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            throw $e;
        }
    }
} 