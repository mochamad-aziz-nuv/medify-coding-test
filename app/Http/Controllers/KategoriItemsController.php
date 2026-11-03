<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KategoriItemsController extends Controller
{
    public function index()
    {
        return view('kategori_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = KategoriItem::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', 'LIKE', '%' . $kode . '%');
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');

        $data_search = $data_search->select('id', 'kode', 'nama')->orderBy('id')->get();

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
            $item = KategoriItem::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        return view('kategori_items.form.index', $data);
    }

    public function singleView($id)
    {
        $data['data'] = KategoriItem::with('masterItems')
            ->where('id', $id)
            ->orWhere('kode', $id)
            ->firstOrFail();
        return view('kategori_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new KategoriItem;
        } else {
            $data_item = KategoriItem::find($id);
        }

        $data_item->kode = $request->kode;
        $data_item->nama = $request->nama;
        $data_item->save();

        return redirect('kategori-items');
    }

    public function delete($id)
    {
        KategoriItem::find($id)->delete();
        return redirect('kategori-items');
    }

    public function downloadPdf($id)
    {
        $data = KategoriItem::with('masterItems')
            ->where('id', $id)
            ->orWhere('kode', $id)
            ->firstOrFail();

        $waktu_cetak = date('d-m-Y H:i:s');
        $pdf = Pdf::loadView('kategori_items.pdf.single', compact('data', 'waktu_cetak'));
        return $pdf->download('kategori_item_' . $data->kode . '.pdf');
    }
}

