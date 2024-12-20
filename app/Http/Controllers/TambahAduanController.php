<?php


namespace App\Http\Controllers;

use App\Models\TambahAduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AduanExport;
use Carbon\Carbon;




class TambahAduanController extends Controller
{
    // Menampilkan daftar pengaduan
    public function index(Request $request)
    {
     $search = $request->input('search');
     $aduans = TambahAduan::with('user')->get();

     $aduans = TambahAduan::query();

     if ($search) {
         $aduans = $aduans->where('description', 'like', "%{$search}%")
                          ->orWhere('province', 'like', "%{$search}%")
                          ->orWhere('regency', 'like', "%{$search}%")
                          ->orWhere('district', 'like', "%{$search}%")
                          ->orWhere('village', 'like', "%{$search}%");
     }
 
     $aduans = $aduans->get();
    //  $aduans = TambahAduan::where('user_id', Auth::id())->get();
 
     return view('tambahAduan.index', compact('aduans'));
 }

    public function AduanGuest(){

        $aduans = TambahAduan::all();
        return view('tambahAduan.tambahAduan', compact('aduans'));
    }

    public function LandingPage(Request $request){
        $aduans = TambahAduan::all();
        return view('landing-page.index', compact('aduans'));
    }

    // Form untuk menambah pengaduan baru
    public function create()
    {
        $provinces = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        $provinces = $provinces->json();
        $types = ['kejahatan', 'pembangunan', 'sosial'];
        return view('tambahAduan.create', compact('provinces','types'));
    }

    // Menyimpan pengaduan baru
    public function store(Request $request)
    {
        $request->validate([
            'province' => 'required',
            'regency' => 'required',
            'district' => 'required',
            'village' => 'required',
            'type' => 'required|in:kejahatan,pembangunan,sosial',
            'description' => 'required',
            'image' => 'required|image',
            'statement' => 'nullable|string',
            
        ]);

        $pengaduan = new TambahAduan();
        $pengaduan->description = $request->description;
        $pengaduan->province = $request->province;
        $pengaduan->regency = $request->regency;
        $pengaduan->district = $request->district;
        $pengaduan->village = $request->village;
        $pengaduan->voting = 0;

        // Menyimpan gambar
        $imagePath = $request->hasFile('image') ? $request->file('image')->store('images', 'public') : null;
        $pengaduan->image = $imagePath;

        $pengaduan->save();

        session()->flash('success', 'Pengaduan berhasil ditambahkan!');
        return redirect()->route('tambahAduanGuest-pengaduan');
    }

    // Menampilkan form edit pengaduan
    public function edit($id)
{
    $pengaduan = TambahAduan::findOrFail($id);

    $provinces = json_decode(file_get_contents('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json'), true);
    $regencies = json_decode(file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$pengaduan->province}.json"), true);
    $districts = json_decode(file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$pengaduan->regency}.json"), true);
    $villages = json_decode(file_get_contents("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$pengaduan->district}.json"), true);

    return view('tambahAduan.edit', compact('pengaduan', 'provinces', 'regencies', 'districts', 'villages'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'province' => 'required',
        'regency' => 'required',
        'district' => 'required',
        'village' => 'required',
        'description' => 'required',
        'image' => 'nullable|image',
    ]);

    $pengaduan = TambahAduan::findOrFail($id);

    $pengaduan->description = $request->description;
    $pengaduan->province = $request->province;
    $pengaduan->regency = $request->regency;
    $pengaduan->district = $request->district;
    $pengaduan->village = $request->village;

    if ($request->hasFile('image')) {
        if ($pengaduan->image && file_exists(public_path('storage/' . $pengaduan->image))) {
            unlink(public_path('storage/' . $pengaduan->image));
        }
        $pengaduan->image = $request->file('image')->store('images', 'public');
    }

    $pengaduan->save();

    return redirect()->route('tambahAduanGuest-pengaduan')->with('success', 'Pengaduan berhasil diperbarui!');
}

public function updateViewCount($aduanId)
{
    // Mengecek apakah aduan sudah pernah dilihat oleh pengguna saat ini
    if (!session()->has("viewed_aduan_{$aduanId}")) {
        $aduan = TambahAduan::find($aduanId);

        if ($aduan) {
            // Menambah jumlah viewers
            $aduan->viewers += 1;
            $aduan->save();

            // Menandai bahwa pengguna telah melihat aduan ini
            session()->put("viewed_aduan_{$aduanId}", true);

            return response()->json(['success' => true]);
        }
    }

    return response()->json(['success' => false, 'message' => 'Aduan not found or already viewed']);
}

    



    public function updateVoting($id)
    {
        // Menambah atau mengurangi voting
        $aduan = TambahAduan::findOrFail($id);
        $aduan->voting += 1;  // Anda bisa mengubah logika ini sesuai kebutuhan (misal: -1 untuk un-vote)
        $aduan->save();

        return redirect()->back();  // Kembali ke halaman yang sama setelah update
    }

    public function addComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);
    
        // Mencari aduan berdasarkan ID
        $aduan = TambahAduan::findOrFail($id);
    
        // Menyimpan komentar beserta email pengguna yang sedang login
        $aduan->comments = $aduan->comments . "\n" . $request->comment . ' - ' . Auth::user()->email; // Menambahkan komentar dan email pengguna
    
        // Menyimpan perubahan pada data aduan
        $aduan->save();
    
        return redirect()->back()->with('success', 'Anda berhasil menambhakan komentar!!'); // Kembali ke halaman yang sama setelah komentar ditambahkan
    }
    
    // Menghapus pengaduan
    public function destroy($id)
    {
        $aduan = TambahAduan::findOrFail($id);

        // Hapus gambar jika ada
        if ($aduan->image && file_exists(public_path('storage/' . $aduan->image))) {
            unlink(public_path('storage/' . $aduan->image));
        }

        $aduan->delete();

        session()->flash('success', 'Pengaduan berhasil dihapus!');
        return redirect()->route('tambahAduanGuest-pengaduan');
    }

    public function show($id)
{
    $aduan = TambahAduan::findOrFail($id);
    
    // Update view count saat melihat aduan
    $this->updateViewCount($id);

    return view('tambahAduan.show', compact('aduan'));
}

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,in_progress,resolved,rejected',
    ]);

    $aduan = TambahAduan::findOrFail($id);
    $aduan->status = $request->status;
    $aduan->save();

    return redirect()->back()->with('success', 'Status pengaduan berhasil diperbarui!');
}













    public function getProvinces()
    {
        $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
        return response()->json($response->json());
    }

    // Get regencies by province ID
    public function getRegencies($province_id)
    {
        $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$province_id}.json");
        return response()->json($response->json());
    }

    // Get districts by regency ID
    public function getDistricts($regency_id)
    {
        $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$regency_id}.json");
        return response()->json($response->json());
    }

    // Get villages by district ID
    public function getVillages($district_id)
    {
        $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$district_id}.json");
        return response()->json($response->json());
    }

   
    
}
