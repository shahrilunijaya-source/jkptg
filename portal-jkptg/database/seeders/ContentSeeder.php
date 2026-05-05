<?php

namespace Database\Seeders;

use App\Models\Cawangan;
use App\Models\ChatbotKnowledge;
use App\Models\ChatbotQuickReply;
use App\Models\ChatbotSetting;
use App\Models\Faq;
use App\Models\Form;
use App\Models\News;
use App\Models\Page;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Tender;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPages();
        $this->seedServices();
        $this->seedNews();
        $this->seedTenders();
        $this->seedForms();
        $this->seedFaqs();
        $this->seedCawangan();
        $this->seedChatbot();
        $this->seedSettings();
    }

    private function seedPages(): void
    {
        $pages = [
            ['utama', 'Utama', 'Home', 'Selamat datang ke portal rasmi JKPTG.', 'Welcome to the official JKPTG portal.'],
            ['mengenai-jkptg', 'Mengenai JKPTG', 'About JKPTG',
                'Jabatan Ketua Pengarah Tanah dan Galian Persekutuan ditubuhkan untuk menguruskan tanah Persekutuan.',
                'JKPTG is the federal department responsible for managing federal land in Malaysia.'],
            ['piagam-pelanggan', 'Piagam Pelanggan', 'Customer Charter',
                'Kami komited memberikan perkhidmatan berkualiti dengan SLA yang telus.',
                'We are committed to delivering quality service with transparent SLAs.'],
            ['visi-misi', 'Visi & Misi', 'Vision & Mission',
                'Visi: Peneraju pengurusan tanah Persekutuan. Misi: Mengurus dengan amanah.',
                'Vision: Leader in federal land management. Mission: Manage with integrity.'],
            ['carta-organisasi', 'Carta Organisasi', 'Organisation Chart',
                'Struktur organisasi JKPTG meliputi 5 bahagian utama dan 14 cawangan negeri.',
                'JKPTG comprises 5 main divisions and 14 state branches.'],
            ['kerjaya', 'Kerjaya', 'Careers',
                'Sertai kami sebagai Penolong Pegawai Tanah, Juruukur, atau Eksekutif Korporat.',
                'Join us as Assistant Land Officer, Surveyor, or Corporate Executive.'],
            ['penerbitan', 'Penerbitan', 'Publications',
                'Akses laporan tahunan, buletin, dan kajian dasar JKPTG.',
                'Access annual reports, bulletins, and policy studies from JKPTG.'],
            ['hubungi-kami', 'Hubungi Kami', 'Contact Us',
                'Ibu pejabat: Aras 6-7, Wisma Sumber Asli, Presint 4, 62574 Putrajaya.',
                'HQ: Levels 6-7, Wisma Sumber Asli, Precinct 4, 62574 Putrajaya.'],
            ['polisi-privasi', 'Polisi Privasi', 'Privacy Policy',
                'JKPTG melindungi data peribadi anda mengikut Akta Perlindungan Data Peribadi 2010.',
                'JKPTG protects your personal data under the Personal Data Protection Act 2010.'],
            ['polisi-keselamatan', 'Polisi Keselamatan', 'Security Policy',
                'Sistem maklumat JKPTG dilindungi mengikut piawai ISO/IEC 27001.',
                'JKPTG information systems are protected per ISO/IEC 27001 standards.'],
            ['disclaimer', 'Penafian', 'Disclaimer',
                'Maklumat di laman ini disediakan sebagai rujukan umum.',
                'Information on this site is provided for general reference.'],
            ['peta-laman', 'Peta Laman', 'Sitemap',
                'Senarai penuh halaman portal JKPTG.',
                'Full list of JKPTG portal pages.'],
        ];

        foreach ($pages as $i => [$slug, $titleMs, $titleEn, $bodyMs, $bodyEn]) {
            Page::updateOrCreate(['slug' => $slug], [
                'title' => ['ms' => $titleMs, 'en' => $titleEn],
                'body' => ['ms' => "<p>{$bodyMs}</p>", 'en' => "<p>{$bodyEn}</p>"],
                'meta_title' => ['ms' => "{$titleMs} | JKPTG", 'en' => "{$titleEn} | JKPTG"],
                'meta_description' => ['ms' => mb_substr($bodyMs, 0, 160), 'en' => mb_substr($bodyEn, 0, 160)],
                'sort' => ($i + 1) * 10,
                'published' => true,
            ]);
        }
    }

    private function seedServices(): void
    {
        $services = [
            [
                'slug' => 'pengambilan-tanah', 'icon' => 'land-plot', 'category' => 'tanah', 'processing_days' => 90,
                'name' => ['ms' => 'Pengambilan Tanah', 'en' => 'Land Acquisition'],
                'summary' => [
                    'ms' => 'Akta Pengambilan Tanah 1960 untuk projek Persekutuan dengan pampasan adil.',
                    'en' => 'Land Acquisition Act 1960 for federal projects with fair compensation.',
                ],
                'eligibility' => [
                    'ms' => 'Tanah dimiliki individu atau syarikat. Projek diluluskan Kerajaan Persekutuan.',
                    'en' => 'Land owned by individual or company. Project approved by Federal Government.',
                ],
                'process_steps' => [
                    'ms' => ['Notis Borang A', 'Siasatan Tapak', 'Tawaran Pampasan', 'Persetujuan / Rayuan', 'Pemilikan Kosong'],
                    'en' => ['Form A Notice', 'Site Investigation', 'Compensation Offer', 'Acceptance / Appeal', 'Vacant Possession'],
                ],
                'required_documents' => [
                    'ms' => ['Salinan IC', 'Geran tanah', 'Surat kuasa wakil'],
                    'en' => ['Copy of IC', 'Land title', 'Power of attorney'],
                ],
            ],
            [
                'slug' => 'pusaka-bukan-islam', 'icon' => 'scroll-text', 'category' => 'tanah', 'processing_days' => 60,
                'name' => ['ms' => 'Pusaka Bukan Islam', 'en' => 'Non-Muslim Estate'],
                'summary' => [
                    'ms' => 'Pembahagian harta pusaka kecil di bawah Akta Harta Pusaka Kecil 1955.',
                    'en' => 'Small estate distribution under the Small Estates Distribution Act 1955.',
                ],
                'eligibility' => [
                    'ms' => 'Si mati bukan beragama Islam. Nilai pusaka tidak melebihi RM 2 juta.',
                    'en' => 'Deceased was not Muslim. Estate value not exceeding RM 2 million.',
                ],
                'process_steps' => [
                    'ms' => ['Permohonan Borang A', 'Siasatan Pusaka', 'Pendengaran', 'Perintah Pembahagian'],
                    'en' => ['Form A Application', 'Estate Investigation', 'Hearing', 'Distribution Order'],
                ],
                'required_documents' => [
                    'ms' => ['Sijil mati', 'Geran tanah si mati', 'Senarai waris'],
                    'en' => ['Death certificate', 'Land titles of deceased', 'List of heirs'],
                ],
            ],
            [
                'slug' => 'pajakan-tanah-persekutuan', 'icon' => 'key-square', 'category' => 'pajakan', 'processing_days' => 120,
                'name' => ['ms' => 'Pajakan Tanah Persekutuan', 'en' => 'Federal Land Lease'],
                'summary' => [
                    'ms' => 'Permohonan pajakan tanah milik Kerajaan Persekutuan untuk pembangunan.',
                    'en' => 'Application to lease federal government land for development.',
                ],
                'eligibility' => [
                    'ms' => 'Syarikat berdaftar atau individu warganegara. Tujuan pembangunan diluluskan.',
                    'en' => 'Registered company or citizen. Approved development purpose.',
                ],
                'process_steps' => [
                    'ms' => ['Permohonan', 'Penilaian', 'Tawaran Pajakan', 'Penyempurnaan Pajakan'],
                    'en' => ['Application', 'Valuation', 'Lease Offer', 'Lease Execution'],
                ],
                'required_documents' => [
                    'ms' => ['Pendaftaran SSM', 'Pelan tapak', 'Cadangan pembangunan'],
                    'en' => ['SSM registration', 'Site plan', 'Development proposal'],
                ],
            ],
            [
                'slug' => 'penyewaan-bangunan', 'icon' => 'home', 'category' => 'pajakan', 'processing_days' => 30,
                'name' => ['ms' => 'Penyewaan Bangunan Kerajaan', 'en' => 'Government Building Rental'],
                'summary' => [
                    'ms' => 'Sewa kuarters atau ruang pejabat milik Kerajaan Persekutuan.',
                    'en' => 'Rent quarters or office space owned by Federal Government.',
                ],
                'eligibility' => [
                    'ms' => 'Penjawat awam atau agensi kerajaan.',
                    'en' => 'Civil servant or government agency.',
                ],
                'process_steps' => [
                    'ms' => ['Permohonan', 'Pemilihan Unit', 'Perjanjian Sewa', 'Pemilikan'],
                    'en' => ['Application', 'Unit Selection', 'Tenancy Agreement', 'Possession'],
                ],
            ],
            [
                'slug' => 'lesen-pasir-batu', 'icon' => 'layers', 'category' => 'lesen', 'processing_days' => 45,
                'name' => ['ms' => 'Lesen Pasir & Batu', 'en' => 'Sand & Stone Permit'],
                'summary' => [
                    'ms' => 'Permit pengeluaran bahan binaan dari tanah Persekutuan.',
                    'en' => 'Permit to extract construction materials from federal land.',
                ],
                'eligibility' => [
                    'ms' => 'Kontraktor berdaftar CIDB. Lokasi diluluskan EIA.',
                    'en' => 'CIDB-registered contractor. EIA-approved location.',
                ],
                'process_steps' => [
                    'ms' => ['Permohonan', 'Inspeksi', 'Bayaran', 'Lesen Dikeluarkan'],
                    'en' => ['Application', 'Inspection', 'Payment', 'Permit Issued'],
                ],
            ],
            [
                'slug' => 'strata-hakmilik', 'icon' => 'building', 'category' => 'strata', 'processing_days' => 90,
                'name' => ['ms' => 'Hakmilik Strata', 'en' => 'Strata Title'],
                'summary' => [
                    'ms' => 'Pendaftaran hakmilik strata untuk bangunan bertingkat.',
                    'en' => 'Strata title registration for multi-storey buildings.',
                ],
                'eligibility' => [
                    'ms' => 'Bangunan siap dengan CCC. Pemaju berdaftar.',
                    'en' => 'Completed building with CCC. Registered developer.',
                ],
                'process_steps' => [
                    'ms' => ['Permohonan Strata', 'Ukuran', 'Pendaftaran', 'Pengeluaran Hakmilik'],
                    'en' => ['Strata Application', 'Survey', 'Registration', 'Title Issuance'],
                ],
            ],
        ];

        foreach ($services as $i => $s) {
            Service::updateOrCreate(['slug' => $s['slug']], array_merge($s, [
                'sort' => ($i + 1) * 10,
                'active' => true,
            ]));
        }
    }

    private function seedNews(): void
    {
        $news = [
            ['lancar-myland-v2', 'berita', true,
                ['ms' => 'JKPTG Lancar Sistem MyLAND v2.0', 'en' => 'JKPTG Launches MyLAND v2.0'],
                ['ms' => 'Sistem baru pengurusan rekod tanah Persekutuan kini lebih pantas.', 'en' => 'New federal land record system is now faster.'],
                Carbon::parse('2026-05-12'),
            ],
            ['webinar-pusaka-jun', 'pengumuman', false,
                ['ms' => 'Webinar Pusaka Bukan Islam 15 Jun', 'en' => 'Non-Muslim Estate Webinar June 15'],
                ['ms' => 'Sesi taklimat percuma untuk waris berhadapan urusan harta pusaka.', 'en' => 'Free briefing for heirs handling estate matters.'],
                Carbon::parse('2026-05-08'),
            ],
            ['sop-pajakan-v32', 'pengumuman', false,
                ['ms' => 'SOP Pajakan v3.2 Berkuat Kuasa 1 Jun', 'en' => 'Lease SOP v3.2 Effective June 1'],
                ['ms' => 'Pengemaskinian SOP melibatkan dokumen sokongan dan tempoh.', 'en' => 'SOP update covers supporting documents and timeline.'],
                Carbon::parse('2026-05-02'),
            ],
            ['tarikh-tutup-tk01', 'pengumuman', true,
                ['ms' => 'Tarikh tutup borang TK01: 30 Mei 2026', 'en' => 'Form TK01 deadline: 30 May 2026'],
                ['ms' => 'Pemohon disaran menghantar awal mengelakkan kesesakan sistem.', 'en' => 'Applicants advised to submit early to avoid system congestion.'],
                Carbon::parse('2026-05-01'),
            ],
            ['ulang-tahun-jkptg', 'berita', false,
                ['ms' => 'JKPTG Sambut Ulang Tahun Ke-30', 'en' => 'JKPTG Celebrates 30th Anniversary'],
                ['ms' => 'Tiga dekad mengurus tanah Persekutuan dengan amanah.', 'en' => 'Three decades managing federal land with integrity.'],
                Carbon::parse('2026-04-25'),
            ],
        ];

        foreach ($news as [$slug, $type, $important, $title, $excerpt, $publishedAt]) {
            News::updateOrCreate(['slug' => $slug], [
                'title' => $title,
                'excerpt' => $excerpt,
                'body' => [
                    'ms' => '<p>' . $excerpt['ms'] . '</p><p>Maklumat lanjut akan dikemaskini.</p>',
                    'en' => '<p>' . $excerpt['en'] . '</p><p>More details will be updated.</p>',
                ],
                'type' => $type,
                'important' => $important,
                'published_at' => $publishedAt,
            ]);
        }
    }

    private function seedTenders(): void
    {
        $tenders = [
            ['t-2026-001', 'JKPTG/T/01/2026',
                ['ms' => 'Pembekalan komputer riba pejabat', 'en' => 'Office laptop supply'],
                Carbon::parse('2026-05-30 17:00'), 'open', 450000.00,
            ],
            ['t-2026-002', 'JKPTG/T/02/2026',
                ['ms' => 'Khidmat penyelenggaraan rangkaian', 'en' => 'Network maintenance services'],
                Carbon::parse('2026-06-15 17:00'), 'open', 1200000.00,
            ],
            ['t-2026-003', 'JKPTG/T/03/2026',
                ['ms' => 'Naik taraf sistem maklumat tanah', 'en' => 'Land information system upgrade'],
                Carbon::parse('2026-04-30 17:00'), 'closed', 8500000.00,
            ],
        ];

        foreach ($tenders as [$slug, $ref, $title, $closesAt, $status, $value]) {
            Tender::updateOrCreate(['slug' => $slug], [
                'reference_no' => $ref,
                'title' => $title,
                'description' => ['ms' => 'Sila rujuk dokumen iklan.', 'en' => 'Please refer to tender documents.'],
                'closes_at' => $closesAt,
                'opens_at' => $closesAt->copy()->subDays(21)->toDateString(),
                'status' => $status,
                'estimated_value_rm' => $value,
            ]);
        }
    }

    private function seedForms(): void
    {
        $forms = [
            ['borang-a-pengambilan', 'pengambilan', '1.2', 'Borang A - Notis Pengambilan Tanah', 'Form A - Land Acquisition Notice', 480_000],
            ['borang-k-pampasan', 'pengambilan', '1.0', 'Borang K - Tawaran Pampasan', 'Form K - Compensation Offer', 320_000],
            ['borang-pp1-pusaka', 'pusaka', '2.1', 'Borang PP1 - Permohonan Pusaka', 'Form PP1 - Estate Application', 510_000],
            ['borang-pj1-pajakan', 'pajakan', '3.2', 'Borang PJ1 - Permohonan Pajakan', 'Form PJ1 - Lease Application', 620_000],
            ['borang-tk01-aduan', 'aduan', '1.0', 'Borang TK01 - Aduan Pelanggan', 'Form TK01 - Customer Complaint', 180_000],
        ];

        foreach ($forms as $i => [$slug, $cat, $ver, $nameMs, $nameEn, $size]) {
            Form::updateOrCreate(['slug' => $slug], [
                'name' => ['ms' => $nameMs, 'en' => $nameEn],
                'description' => ['ms' => 'Borang rasmi JKPTG. Sila lengkapkan dengan tepat.', 'en' => 'Official JKPTG form. Please complete accurately.'],
                'file_path' => "borang/{$slug}.pdf",
                'category' => $cat,
                'version' => $ver,
                'file_size_bytes' => $size,
                'downloads_count' => rand(50, 5000),
                'active' => true,
            ]);
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            ['umum', 'Apakah peranan JKPTG?', 'What is JKPTG\'s role?',
                'JKPTG menguruskan rekod, pelupusan dan pengambilan tanah Persekutuan.',
                'JKPTG manages federal land records, disposal, and acquisition.'],
            ['umum', 'Bagaimana hubungi JKPTG?', 'How to contact JKPTG?',
                'Telefon 03-8000 8000 atau emel webmaster@jkptg.gov.my.',
                'Phone 03-8000 8000 or email webmaster@jkptg.gov.my.'],
            ['pengambilan', 'Berapa lama proses pengambilan tanah?', 'How long does land acquisition take?',
                'Lazimnya 90 hari dari tarikh Notis Borang A.',
                'Typically 90 days from Form A Notice date.'],
            ['pengambilan', 'Bolehkah saya merayu pampasan?', 'Can I appeal the compensation?',
                'Ya. Rayuan ke Mahkamah Tinggi dalam tempoh 6 minggu.',
                'Yes. Appeal to High Court within 6 weeks.'],
            ['pusaka', 'Apakah had nilai pusaka kecil?', 'What is the small estate value cap?',
                'Tidak melebihi RM 2 juta.', 'Not exceeding RM 2 million.'],
            ['pajakan', 'Berapa lama tempoh pajakan biasa?', 'What is the typical lease term?',
                'Antara 30 hingga 99 tahun bergantung pada tujuan.',
                'Between 30 and 99 years depending on purpose.'],
            ['lesen', 'Adakah lesen pasir tertakluk EIA?', 'Are sand permits subject to EIA?',
                'Ya, untuk operasi skala besar.', 'Yes, for large-scale operations.'],
            ['umum', 'Bolehkah saya membuat aduan secara dalam talian?', 'Can I file a complaint online?',
                'Ya, melalui SISPAA atau Borang TK01.', 'Yes, via SISPAA or Form TK01.'],
        ];

        foreach ($faqs as $i => [$cat, $qMs, $qEn, $aMs, $aEn]) {
            Faq::updateOrCreate(
                ['category' => $cat, 'sort' => ($i + 1) * 10],
                [
                    'question' => ['ms' => $qMs, 'en' => $qEn],
                    'answer' => ['ms' => $aMs, 'en' => $aEn],
                    'active' => true,
                ]
            );
        }
    }

    private function seedCawangan(): void
    {
        $cawangan = [
            ['ibu-pejabat', 'Ibu Pejabat JKPTG', 'JKPTG Headquarters', 'WP Putrajaya',
                ['ms' => 'Aras 6-7, Wisma Sumber Asli, Presint 4, 62574 Putrajaya', 'en' => 'Levels 6-7, Wisma Sumber Asli, Precinct 4, 62574 Putrajaya'],
                '03-8000 8000', '03-8000 8001', 'webmaster@jkptg.gov.my', 2.9264, 101.6964, true],
            ['cawangan-selangor', 'Cawangan Selangor', 'Selangor Branch', 'Selangor',
                ['ms' => 'Wisma Negeri, 40000 Shah Alam', 'en' => 'Wisma Negeri, 40000 Shah Alam'],
                '03-5519 6000', null, 'selangor@jkptg.gov.my', 3.0738, 101.5183, false],
            ['cawangan-pulau-pinang', 'Cawangan Pulau Pinang', 'Penang Branch', 'Pulau Pinang',
                ['ms' => 'Bangunan Persekutuan, 10300 Georgetown', 'en' => 'Federal Building, 10300 Georgetown'],
                '04-262 5000', null, 'penang@jkptg.gov.my', 5.4141, 100.3288, false],
            ['cawangan-johor', 'Cawangan Johor', 'Johor Branch', 'Johor',
                ['ms' => 'Bangunan Sultan Ibrahim, 80000 Johor Bahru', 'en' => 'Sultan Ibrahim Building, 80000 Johor Bahru'],
                '07-222 8000', null, 'johor@jkptg.gov.my', 1.4655, 103.7578, false],
        ];

        foreach ($cawangan as $i => [$slug, $nameMs, $nameEn, $state, $address, $phone, $fax, $email, $lat, $lng, $isHq]) {
            Cawangan::updateOrCreate(['slug' => $slug], [
                'name' => ['ms' => $nameMs, 'en' => $nameEn],
                'state' => $state,
                'address' => $address,
                'phone' => $phone,
                'fax' => $fax,
                'email' => $email,
                'lat' => $lat,
                'lng' => $lng,
                'opening_hours' => [
                    'ms' => 'Isnin-Khamis 8:00 pagi - 5:00 petang. Jumaat 8:00 pagi - 5:00 petang (rehat 12:15-2:45).',
                    'en' => 'Mon-Thu 8am-5pm. Fri 8am-5pm (break 12:15-2:45).',
                ],
                'is_headquarters' => $isHq,
                'sort' => ($i + 1) * 10,
            ]);
        }
    }

    private function seedChatbot(): void
    {
        $kb = [
            ['kb-pengambilan-tempoh', 'pengambilan', ['pengambilan', 'tempoh', 'masa', 'berapa', 'lama'],
                'Berapa lama proses pengambilan tanah?', 'How long does land acquisition take?',
                'Proses pengambilan tanah Persekutuan biasanya mengambil masa 90 hari dari tarikh Notis Borang A diserahkan.',
                'Federal land acquisition typically takes 90 days from the Form A Notice date.'],
            ['kb-pengambilan-pampasan', 'pengambilan', ['pampasan', 'rayuan', 'mahkamah', 'tidak puas'],
                'Bolehkah saya merayu jumlah pampasan?', 'Can I appeal the compensation amount?',
                'Ya. Rayuan boleh dikemukakan ke Mahkamah Tinggi dalam tempoh 6 minggu dari tarikh tawaran.',
                'Yes. Appeals may be filed to the High Court within 6 weeks of the offer date.'],
            ['kb-pusaka-had', 'pusaka', ['had', 'nilai', 'pusaka', 'kecil', 'maksimum'],
                'Apakah had nilai pusaka kecil?', 'What is the small estate value cap?',
                'Pusaka kecil bukan Islam tertakluk pada nilai tidak melebihi RM 2 juta.',
                'Non-Muslim small estates are limited to a value not exceeding RM 2 million.'],
            ['kb-pajakan-tempoh', 'pajakan', ['pajakan', 'tempoh', 'tahun', 'lease'],
                'Berapa lama tempoh pajakan tanah Persekutuan?', 'How long is a federal land lease?',
                'Tempoh pajakan biasanya antara 30 hingga 99 tahun bergantung pada tujuan pembangunan.',
                'Lease terms are typically 30 to 99 years depending on development purpose.'],
            ['kb-borang-muat-turun', 'umum', ['borang', 'muat turun', 'pdf', 'download'],
                'Di mana saya boleh muat turun borang?', 'Where can I download forms?',
                'Semua borang JKPTG tersedia di halaman Panduan & Borang. Klik Muat Turun di sebelah setiap borang.',
                'All JKPTG forms are available at the Guides & Forms page. Click Download next to each form.'],
            ['kb-aduan', 'umum', ['aduan', 'sispaa', 'tk01', 'lapor'],
                'Bagaimana saya boleh membuat aduan?', 'How do I file a complaint?',
                'Anda boleh lapor melalui SISPAA atau muat turun Borang TK01 dari portal.',
                'You may file via SISPAA or download Form TK01 from the portal.'],
        ];

        foreach ($kb as [$slug, $cat, $kw, $qMs, $qEn, $aMs, $aEn]) {
            ChatbotKnowledge::updateOrCreate(['slug' => $slug], [
                'question' => ['ms' => $qMs, 'en' => $qEn],
                'answer' => ['ms' => $aMs, 'en' => $aEn],
                'keywords' => $kw,
                'category' => $cat,
                'active' => true,
            ]);
        }

        $quickReplies = [
            ['Pengambilan tanah', 'Land acquisition', 'pengambilan tanah'],
            ['Pusaka bukan Islam', 'Non-Muslim estate', 'pusaka'],
            ['Pajakan tanah', 'Land lease', 'pajakan'],
            ['Muat turun borang', 'Download forms', 'borang muat turun'],
        ];

        foreach ($quickReplies as $i => [$labelMs, $labelEn, $payload]) {
            ChatbotQuickReply::updateOrCreate(
                ['payload_query' => $payload],
                [
                    'label' => ['ms' => $labelMs, 'en' => $labelEn],
                    'sort' => ($i + 1) * 10,
                    'active' => true,
                ]
            );
        }

        ChatbotSetting::updateOrCreate(['id' => 1], [
            'driver' => env('LLM_DRIVER', 'canned'),
            'model' => 'claude-sonnet-4-6',
            'cost_month_to_date_rm' => 0,
            'cost_cap_rm' => env('LLM_COST_CAP_RM', 200),
            'alert_threshold_pct' => 80,
            'kill_switch_active' => false,
            'cap_reset_at' => Carbon::now()->startOfMonth()->addMonth(),
        ]);
    }

    private function seedSettings(): void
    {
        $settings = [
            ['site.title', ['ms' => 'Portal Rasmi JKPTG', 'en' => 'JKPTG Official Portal'], 'Site title'],
            ['site.tagline', ['ms' => 'Mengurus tanah Persekutuan dengan amanah', 'en' => 'Managing federal land with integrity'], 'Hero tagline'],
            ['site.email', 'webmaster@jkptg.gov.my', 'Primary contact email'],
            ['site.phone', '03-8000 8000', 'Primary phone'],
            ['site.address', ['ms' => 'Aras 6-7, Wisma Sumber Asli, Presint 4, 62574 Putrajaya', 'en' => 'Levels 6-7, Wisma Sumber Asli, Precinct 4, 62574 Putrajaya'], 'HQ address'],
            ['site.last_updated', now()->toIso8601String(), 'Last content update timestamp'],
            ['site.visitor_count', 1234567, 'Cumulative visitor count (mockup placeholder)'],
            ['social.facebook', 'https://facebook.com/jkptg', 'Facebook URL'],
            ['social.twitter', 'https://twitter.com/jkptg', 'Twitter / X URL'],
            ['social.youtube', 'https://youtube.com/@jkptg', 'YouTube URL'],
        ];

        foreach ($settings as [$key, $value, $description]) {
            Setting::put($key, $value, $description);
        }
    }
}
