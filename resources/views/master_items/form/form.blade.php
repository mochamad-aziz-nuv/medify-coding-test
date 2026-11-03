<form method="POST" enctype="multipart/form-data">
    @csrf
    @if($method == 'edit')
    <div class="form-group">
        <label>Kode Barang</label>
        <input type="text" class="form-control" name="kode_barang" required readonly value="{{$item->kode ?? ''}}">
    </div>
    @endif

    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="nama" required  value="{{$item->nama ?? ''}}">
    </div>

    <div class="form-group">
        <label>Harga Beli</label>
        <input type="number" class="form-control" name="harga_beli" required  value="{{$item->harga_beli ?? ''}}">
    </div>

    <div class="form-group">
        <label>Laba (dalam persen)</label>
        <input type="number" class="form-control" name="laba" required  value="{{$item->laba ?? ''}}">
    </div>

    @php $selected = $item->supplier ?? ''; @endphp
    <div class="form-group">
        <label>Supplier</label>
        <select class="form-control" required name="supplier">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Tokopaedi') selected @endif>Tokopaedi</option>
            <option @if($selected == 'Bukulapuk') selected @endif>Bukulapuk</option>
            <option @if($selected == 'TokoBagas') selected @endif>TokoBagas</option>
            <option @if($selected == 'E Commurz') selected @endif>E Commurz</option>
            <optio @if($selected == 'Blublu') selected @endif>Blublu</option>
        </select>
    </div>

    @php $selected = $item->jenis ?? ''; @endphp
    <div class="form-group">
        <label>Jenis</label>
        <select class="form-control" required name="jenis">
            <option @if($selected == '') selected @endif value="">--Pilih--</option>
            <option @if($selected == 'Obat') selected @endif>Obat</option>
            <option @if($selected == 'Alkes') selected @endif>Alkes</option>
            <option @if($selected == 'Matkes') selected @endif>Matkes</option>
            <optio @if($selected == 'Umum') selected @endif>Umum</option>
            <optio @if($selected == 'ATK') selected @endif>ATK</option>
        </select>
    </div>

    @php
        $selectedKategoris = ($method == 'edit' && !empty($item) && isset($item->kategoriItems))
            ? $item->kategoriItems->pluck('id')->toArray()
            : [];
    @endphp
    <div class="form-group mt-2">
        <label>Kategori Items</label>
        <select class="form-control select2-kategori" name="kategori_ids[]" multiple="multiple" style="width: 100%;">
            @if(isset($kategoris))
                @foreach($kategoris as $kategori)
                    <option value="{{ $kategori->id }}" @if(in_array($kategori->id, $selectedKategoris)) selected @endif>
                        {{ $kategori->kode }} - {{ $kategori->nama }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>

    <div class="form-group mt-2">
        <label>Foto / Gambar Barang</label>
        @if($method == 'edit' && !empty($item->foto))
            <div class="mb-2">
                <img src="{{ asset('uploads/master_items/' . $item->foto) }}" alt="Foto {{ $item->nama }}" class="img-thumbnail" style="max-height: 150px;">
                <small class="d-block text-muted">File saat ini: {{ $item->foto }}</small>
            </div>
        @endif
        <input type="file" class="form-control" name="foto" accept="image/*">
        @if($method == 'edit')
            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah gambar.</small>
        @endif
    </div>

    <button class="btn btn-primary mt-3">Submit</button>

</form>