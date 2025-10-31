<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <style>
    *{ font-family: DejaVu Sans, Arial, sans-serif; }
    body{ font-size:12px; color:#222; }
    h2{ margin:0 0 6px 0; }
    table{ width:100%; border-collapse:collapse; }
    th, td{ border:1px solid #999; padding:6px 8px; vertical-align:top; }
    th{ background:#eef5ff; }
    .muted{ color:#666; }
    .center{ text-align:center; }
    .right{ text-align:right; }
    .mb-8{ margin-bottom:16px; }
    .mb-4{ margin-bottom:10px; }
    .badge{ display:inline-block; padding:2px 8px; border-radius:12px; font-weight:bold; }
    .ok{ background:#d1fadf; color:#137a2a; }
    .no{ background:#fee2e2; color:#991b1b; }
  </style>
</head>
<body>
  <table class="mb-8">
    <tr>
      <td><b>No. Dokumen</b> : {{ $noDok }}</td>
      <td class="right"><b>Tanggal Terbit</b> : {{ $tanggal }}</td>
    </tr>
    <tr>
      <td colspan="2"><b>Nama</b> : {{ $karyawan->nama }} &nbsp; <span class="muted">({{ $karyawan->kode }})</span></td>
    </tr>
  </table>

  <h2>Data KPI 12 Bulan ({{ $tahun }})</h2>
  <table class="mb-8">
    <thead>
      <tr>
        <th style="width:24px" class="center">No</th>
        <th>KPI</th>
        <th class="center" style="width:60px">Bobot</th>
        @for($m=1;$m<=12;$m++) <th class="center" style="width:36px">{{ $m }}</th> @endfor
      </tr>
    </thead>
    <tbody>
      @php $no=1; $gk=null; @endphp
      @foreach($subs as $s)
        @if($gk !== $s->kode_kriteria)
          <tr>
            <td class="center"><b>{{ $no }}</b></td>
            <td colspan="{{ 14 }}"><b>{{ $s->kode_kriteria }} — {{ $s->nama_kriteria }} ({{ ucfirst($s->tipe) }})</b></td>
          </tr>
          @php $no++; $gk = $s->kode_kriteria; @endphp
        @endif
        <tr>
          <td class="center"></td>
          <td>{{ $s->kode_sub }} — {{ $s->nama_sub_kriteria }}</td>
          <td class="center">{{ (int)round($s->bobot*100) }}%</td>
          @for($m=1;$m<=12;$m++)
            @php $v = $kpi[$s->id][$m]; @endphp
            <td class="center">{{ is_null($v)?'–':(int)round($v*100) }}%</td>
          @endfor
        </tr>
      @endforeach
    </tbody>
  </table>

  <h2>Hasil Normalisasi KPI (12 Bulan)</h2>
  <table class="mb-8">
    <thead>
      <tr>
        @foreach($subs as $s)
          <th class="center">{{ $s->kode_sub }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      <tr>
        @foreach($subs as $s)
          @php $r = $norm[$s->id]; @endphp
          <td class="center">{{ is_null($r)?'–':(int)round($r*100) }}%</td>
        @endforeach
      </tr>
    </tbody>
  </table>

  <h2 class="mb-4">Hasil Akhir</h2>
  <table class="mb-4">
    <tr>
      <td><b>Nilai Akhir</b></td>
      <td class="right"><b>{{ $scorePct }}%</b></td>
    </tr>
    <tr>
      <td colspan="2">
        @if($eligible)
          Dengan hasil akhir {{ $scorePct }}% maka karyawan atas nama <b>{{ $karyawan->nama }}</b> dinyatakan <span class="badge ok">NAIK GAJI</span>.
        @else
          Dengan hasil akhir {{ $scorePct }}% maka karyawan atas nama <b>{{ $karyawan->nama }}</b> dinyatakan <span class="badge no">TIDAK NAIK GAJI</span>. (batas ≥ {{ $threshold }}%)
        @endif
      </td>
    </tr>
  </table>
</body>
</html>
