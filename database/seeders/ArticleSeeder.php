<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->oldest('id')->first();

        if (! $admin) {
            $this->command?->warn('Artikel awal tidak dibuat karena akun admin belum tersedia.');
            return;
        }

        $articles = [
            [
                'title' => 'Memahami Risiko Rantai Pasok Global',
                'slug' => 'memahami-risiko-rantai-pasok-global',
                'summary' => 'Panduan membaca indikator cuaca, ekonomi, mata uang, dan berita untuk menilai risiko pengadaan internasional.',
                'content' => "Risiko rantai pasok global muncul ketika perubahan cuaca, ekonomi, nilai tukar, atau kondisi geopolitik mengganggu aliran barang antarnegara.\n\nCargoVision menggabungkan indikator tersebut ke dalam satu skor risiko. Skor ini membantu pengguna menentukan negara yang perlu dipantau lebih dekat, tetapi tetap harus digunakan bersama pertimbangan bisnis dan informasi operasional terbaru.\n\nPerusahaan dapat mengurangi risiko dengan menyiapkan pemasok alternatif, menambah waktu cadangan, memantau pelabuhan tujuan, dan mengevaluasi perubahan biaya secara berkala.",
                'category' => 'Supply Chain',
                'featured' => true,
            ],
            [
                'title' => 'Cuaca Ekstrem dan Dampaknya terhadap Logistik Maritim',
                'slug' => 'cuaca-ekstrem-dan-logistik-maritim',
                'summary' => 'Hujan, angin kencang, dan badai dapat memengaruhi jadwal kapal, aktivitas pelabuhan, serta keselamatan muatan.',
                'content' => "Cuaca ekstrem dapat menyebabkan penundaan keberangkatan, perubahan rute, pembatasan bongkar muat, dan peningkatan biaya operasional. Dampaknya akan lebih besar pada jalur yang memiliki sedikit alternatif pelabuhan.\n\nGlobal Weather Monitoring pada CargoVision membantu pengguna mengamati temperatur, curah hujan, dan kecepatan angin berdasarkan negara. Informasi ini dapat digunakan sebagai peringatan awal sebelum menentukan jadwal pengiriman.\n\nUntuk keputusan operasional, pengguna tetap perlu mengonfirmasi kondisi terkini kepada operator pelabuhan dan penyedia transportasi.",
                'category' => 'Weather',
                'featured' => false,
            ],
            [
                'title' => 'Mengukur Dampak Inflasi dan Nilai Tukar pada Biaya Impor',
                'slug' => 'dampak-inflasi-dan-nilai-tukar-pada-biaya-impor',
                'summary' => 'Inflasi dan perubahan kurs dapat meningkatkan harga barang, biaya produksi, dan kebutuhan modal impor.',
                'content' => "Inflasi menggambarkan perubahan tingkat harga dalam suatu negara, sedangkan nilai tukar menentukan biaya konversi mata uang dalam transaksi internasional. Keduanya dapat mengubah total biaya impor meskipun harga dasar produk tidak berubah.\n\nEconomic Indicators dan Currency Impact pada CargoVision membantu pengguna membandingkan kondisi antarnegara. Perubahan yang tajam perlu diperhatikan ketika menyusun anggaran dan memilih pemasok.\n\nStrategi yang dapat dipertimbangkan mencakup evaluasi kontrak, diversifikasi pemasok, penyesuaian waktu pembelian, dan penyediaan cadangan biaya.",
                'category' => 'Economy',
                'featured' => false,
            ],
            [
                'title' => 'Cara Menggunakan Port Map untuk Perencanaan Rute',
                'slug' => 'menggunakan-port-map-untuk-perencanaan-rute',
                'summary' => 'Port Map membantu menemukan lokasi pelabuhan dan membandingkan pilihan titik asal maupun tujuan pengiriman.',
                'content' => "Pemilihan pelabuhan memengaruhi jarak, waktu transit, akses transportasi lanjutan, dan tingkat gangguan yang mungkin terjadi. Port Map CargoVision menyediakan lokasi pelabuhan dunia berdasarkan dataset publik UN/LOCODE.\n\nPengguna dapat mencari pelabuhan berdasarkan nama atau negara, kemudian menghubungkannya dengan informasi risiko negara, cuaca, dan berita. Pendekatan ini memberi gambaran awal sebelum memilih rute.\n\nData peta berfungsi untuk monitoring dan analisis. Jadwal kapal serta posisi kapal nyata tetap harus dikonfirmasi melalui penyedia layanan pelayaran.",
                'category' => 'Logistics',
                'featured' => false,
            ],
        ];

        foreach ($articles as $article) {
            Article::firstOrCreate(
                ['slug' => $article['slug']],
                $article + [
                    'author_id' => $admin->id,
                    'status' => 'Published',
                ]
            );
        }

        $this->command?->info('Artikel analisis awal tersedia.');
    }
}
