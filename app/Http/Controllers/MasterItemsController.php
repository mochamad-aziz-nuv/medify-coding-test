<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use App\Models\MasterItem;
use Illuminate\Http\Request;

class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if ($request->filled('kode')) $data_search = $data_search->where('kode', $kode);
        if ($request->filled('nama')) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        if ($request->filled('hargamin')) $data_search = $data_search->where('harga_beli', '>=', $hargamin);
        if ($request->filled('hargamax')) $data_search = $data_search->where('harga_beli', '<=', $hargamax);

        $data_search = $data_search->with('kategoriItems')->select('id', 'kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier')->orderBy('id')->get();


        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = MasterItem::with('kategoriItems')->find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        $data['kategoris'] = KategoriItem::orderBy('nama')->get();
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::with('kategoriItems')->where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $tujuan_upload = public_path('uploads/master_items');

            if (!file_exists($tujuan_upload)) {
                mkdir($tujuan_upload, 0755, true);
            }

            if ($method == 'edit' && !empty($data_item->foto)) {
                $old_file = $tujuan_upload . '/' . $data_item->foto;
                if (file_exists($old_file)) {
                    @unlink($old_file);
                }
            }

            $nama_foto = time() . '_' . $file->getClientOriginalName();
            $file->move($tujuan_upload, $nama_foto);
            $data_item->foto = $nama_foto;
        }

        $data_item->save();
        $data_item->kategoriItems()->sync($request->kategori_ids ?? []);

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function downloadExcel()
    {
        $items = MasterItem::with('kategoriItems')->orderBy('id')->get();
        $filename = 'master_items_' . date('Ymd_His') . '.xls';

        return response()->streamDownload(function() use ($items) {
            echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>';
            echo '<body>';
            echo '<table border="1">';
            echo '<thead>';
            echo '<tr style="background-color: #d9edf7; font-weight: bold;">';
            echo '<th>No</th>';
            echo '<th>Nama kategori</th>';
            echo '<th>Nama items</th>';
            echo '<th>Nama supplier</th>';
            echo '<th>Harga</th>';
            echo '<th>Laba</th>';
            echo '<th>Harga jual</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            foreach ($items as $index => $item) {
                $kategoris = ($item->kategoriItems && $item->kategoriItems->count() > 0)
                    ? $item->kategoriItems->pluck('nama')->implode(', ')
                    : '-';
                $harga_jual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);
                $harga_jual_rp = 'Rp ' . number_format($harga_jual, 0, ',', '.');

                echo '<tr>';
                echo '<td style="text-align: center;">' . ($index + 1) . '</td>';
                echo '<td>' . htmlspecialchars($kategoris) . '</td>';
                echo '<td>' . htmlspecialchars($item->nama) . '</td>';
                echo '<td>' . htmlspecialchars($item->supplier) . '</td>';
                echo '<td style="text-align: right;">' . $item->harga_beli . '</td>';
                echo '<td style="text-align: center;">' . $item->laba . '%</td>';
                echo '<td style="text-align: right;">' . $harga_jual_rp . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</body>';
            echo '</html>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0'
        ]);
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach($data as $item)
        {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'];
        $random = rand(0,4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat','Alkes','Matkes','Umum','ATK'];
        $random = rand(0,4);
        return $array[$random];
    }
}
