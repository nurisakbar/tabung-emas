<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'title' => 'Promo Tabungan Emas Bulan Ini',
                'description' => 'Dapatkan bonus 0.1 gram emas untuk setiap pembelian minimal 5 gram. Promo berlaku hingga akhir bulan. Syarat dan ketentuan berlaku.',
                'image' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&h=400&fit=crop',
                'event_date' => Carbon::now()->addDays(5),
                'event_time' => '09:00:00',
                'location' => 'Online',
                'status' => 'upcoming',
                'is_featured' => true,
            ],
            [
                'title' => 'Webinar Investasi Emas untuk Pemula',
                'description' => 'Pelajari dasar-dasar investasi emas dan tips memulai tabungan emas dari para ahli. Gratis untuk semua nasabah. Daftar sekarang!',
                'image' => 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=800&h=400&fit=crop',
                'event_date' => Carbon::now()->addDays(10),
                'event_time' => '14:00:00',
                'location' => 'Zoom Meeting',
                'status' => 'upcoming',
                'is_featured' => true,
            ],
            [
                'title' => 'Program Loyalty Nasabah Setia',
                'description' => 'Dapatkan reward khusus untuk nasabah yang aktif menabung emas. Poin dapat ditukar dengan berbagai hadiah menarik. Mulai dari 1 Januari 2024.',
                'image' => 'https://images.unsplash.com/photo-1606166188517-5ad47ea5e0a0?w=800&h=400&fit=crop',
                'event_date' => Carbon::now()->addDays(15),
                'event_time' => '10:00:00',
                'location' => 'Semua Cabang',
                'status' => 'upcoming',
                'is_featured' => false,
            ],
            [
                'title' => 'Grand Opening Cabang Baru Jakarta Selatan',
                'description' => 'Rayakan pembukaan cabang baru kami di Jakarta Selatan. Dapatkan promo khusus dan hadiah menarik untuk 100 nasabah pertama.',
                'image' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=800&h=400&fit=crop',
                'event_date' => Carbon::now()->addDays(7),
                'event_time' => '10:00:00',
                'location' => 'Jakarta Selatan',
                'status' => 'upcoming',
                'is_featured' => true,
            ],
            [
                'title' => 'Workshop Analisis Pasar Emas',
                'description' => 'Pelajari teknik analisis pasar emas dari analis profesional. Workshop ini akan membahas faktor-faktor yang mempengaruhi harga emas.',
                'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800&h=400&fit=crop',
                'event_date' => Carbon::now()->addDays(12),
                'event_time' => '13:00:00',
                'location' => 'Jakarta Pusat',
                'status' => 'upcoming',
                'is_featured' => false,
            ],
            [
                'title' => 'Cashback 2% untuk Pembelian Emas',
                'description' => 'Dapatkan cashback 2% untuk setiap pembelian emas minimal 10 gram. Promo terbatas hanya untuk 500 nasabah pertama.',
                'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=400&fit=crop',
                'event_date' => Carbon::now()->addDays(3),
                'event_time' => '00:00:00',
                'location' => 'Online',
                'status' => 'upcoming',
                'is_featured' => true,
            ],
            [
                'title' => 'Seminar Perencanaan Keuangan dengan Emas',
                'description' => 'Pelajari cara merencanakan keuangan jangka panjang menggunakan emas sebagai instrumen investasi. Seminar gratis untuk semua peserta.',
                'image' => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=800&h=400&fit=crop',
                'event_date' => Carbon::now()->addDays(20),
                'event_time' => '09:00:00',
                'location' => 'Bandung',
                'status' => 'upcoming',
                'is_featured' => false,
            ],
            [
                'title' => 'Promo Hari Raya - Diskon Biaya Admin',
                'description' => 'Rayakan hari raya bersama kami. Dapatkan diskon 50% biaya admin untuk semua transaksi pembelian emas selama periode promo.',
                'image' => 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=800&h=400&fit=crop',
                'event_date' => Carbon::now()->addDays(25),
                'event_time' => '00:00:00',
                'location' => 'Semua Cabang',
                'status' => 'upcoming',
                'is_featured' => true,
            ],
            [
                'title' => 'Kompetisi Trading Emas Virtual',
                'description' => 'Ikuti kompetisi trading emas virtual dan menangkan hadiah total puluhan juta rupiah. Cocok untuk belajar trading tanpa risiko.',
                'image' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&h=400&fit=crop',
                'event_date' => Carbon::now()->addDays(18),
                'event_time' => '08:00:00',
                'location' => 'Online',
                'status' => 'upcoming',
                'is_featured' => false,
            ],
            [
                'title' => 'Meet & Greet dengan CEO Tabung Emas',
                'description' => 'Kesempatan langka untuk bertemu langsung dengan CEO Tabung Emas. Dapatkan insight tentang masa depan investasi emas di Indonesia.',
                'image' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?w=800&h=400&fit=crop',
                'event_date' => Carbon::now()->addDays(30),
                'event_time' => '15:00:00',
                'location' => 'Jakarta Pusat',
                'status' => 'upcoming',
                'is_featured' => true,
            ],
            [
                'title' => 'Program Tabungan Emas untuk Pelajar',
                'description' => 'Khusus untuk pelajar dan mahasiswa! Mulai tabungan emas dengan modal minimal. Dapatkan edukasi investasi gratis.',
                'image' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=800&h=400&fit=crop',
                'event_date' => Carbon::now()->addDays(22),
                'event_time' => '10:00:00',
                'location' => 'Online',
                'status' => 'upcoming',
                'is_featured' => false,
            ],
            [
                'title' => 'Launching Aplikasi Mobile Tabung Emas',
                'description' => 'Rayakan peluncuran aplikasi mobile kami! Download sekarang dan dapatkan bonus 0.05 gram emas untuk 1000 pengguna pertama.',
                'image' => 'https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=800&h=400&fit=crop',
                'event_date' => Carbon::now()->addDays(8),
                'event_time' => '12:00:00',
                'location' => 'Online',
                'status' => 'upcoming',
                'is_featured' => true,
            ],
        ];

        foreach ($events as $event) {
            Event::firstOrCreate(
                ['title' => $event['title'], 'event_date' => $event['event_date']],
                $event
            );
        }
    }
}
