<?php

namespace App\Http\Controllers;

use App\Models\mahasiswa;
use Illuminate\Http\Request;
use Session;

class mahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $katakunci = $request->katakunci;
        $jumlahbaris = 5;
        if(strlen($katakunci)) {
            $data = mahasiswa::where('nim', 'like',"%$katakunci%")
                ->orWhere('nama', 'like', "%$katakunci%")
                ->orWhere('kelas', 'like', "%$katakunci%")
                ->paginate($jumlahbaris);
        }else{
            $data = mahasiswa::orderBy('nim','desc')->paginate($jumlahbaris);
        }
        return view('mahasiswa.index')->with('data', $data);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mahasiswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Session::flash('nim',$request->nim);
        Session::flash('nama',$request->nama);
        Session::flash('kelas',$request->kelas);
        Session::flash('prodi',$request->prodi);
        $request->validate([
            'nim'=>'required|numeric|unique:mahasiswa,nim',
            'nama'=>'required',
            'kelas'=>'required',
            'prodi'=>'required',
        ],[
            'nim.required'=>'NIM wajib diisi',
            'nim.numeric'=>'NIM harus angka',
            'nim.unique'=>'NIM sudah ada',
            'nama.required'=>'nama wajib diisi',
            'kelas.required'=>'kelas wajib diisi',
            'prodi.required'=>'program studi wajib diisi'
        ]);
        $data = [
            'nim' => $request->nim,
            'nama' => $request->nama,
            'kelas' => $request->kelas,            
            'prodi' => $request->prodi,
        ];
        mahasiswa::create($data);
        return redirect()->to('mahasiswa')->with('success','Berhasil menambahkan data');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = mahasiswa::where('nim', $id)->first();
        return view('mahasiswa.edit')->with('data',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama'=>'required',
            'kelas'=>'required',
            'prodi'=>'required',
        ],[
            'nama.required'=>'nama wajib diisi',
            'kelas.required'=>'kelas wajib diisi',
            'prodi.required'=>'program studi wajib diisi'
        ]);
        $data = [
            'nama' => $request->nama,
            'kelas' => $request->kelas,            
            'prodi' => $request->prodi,
        ];
        mahasiswa::where('nim',$id)->update($data);
        return redirect()->to('mahasiswa')->with('success','Berhasil update data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        mahasiswa::where('nim',$id)->delete();
        return redirect()->to('mahasiswa')->with('success','Berhasil hapus data');
    }
}
