<?php

return [
    'required' => ':Attribute wajib diisi.',
    'string' => ':Attribute harus berupa teks.',
    'integer' => ':Attribute harus berupa angka bulat.',
    'date' => ':Attribute bukan tanggal yang valid.',
    'before_or_equal' => ':Attribute tidak boleh melebihi waktu sekarang.',
    'exists' => ':Attribute yang dipilih tidak valid.',
    'in' => ':Attribute yang dipilih tidak valid.',
    'file' => ':Attribute harus berupa berkas.',
    'mimes' => ':Attribute harus berformat: :values.',
    'uploaded' => ':Attribute gagal diunggah.',
    'min' => [
        'numeric' => ':Attribute minimal :min.',
        'string' => ':Attribute minimal :min karakter.',
    ],
    'max' => [
        'numeric' => ':Attribute maksimal :max.',
        'string' => ':Attribute maksimal :max karakter.',
        'file' => ':Attribute tidak boleh lebih dari :max KB.',
    ],
    'attributes' => [
        'sekolah_id' => 'sekolah',
        'distribusi_id' => 'distribusi',
        'waktu_kejadian' => 'waktu kejadian',
        'deskripsi' => 'deskripsi',
        'gejala' => 'gejala',
        'jumlah_terdampak' => 'jumlah terdampak',
        'bukti' => 'bukti pendukung',
        'isi' => 'catatan',
        'pihak' => 'pihak',
        'keterangan' => 'keterangan',
        'email' => 'email',
        'password' => 'kata sandi',
    ],
];