<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TMMS-SCPK</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200 h-screen ">

    <!-- Sidebar -->
    <aside class="w-64 bg-[#121212] text-gray-300 h-full flex flex-col p-5 space-y-6 shadow-lg fixed">
        <div class="flex items-center justify-between">
            <h1 class="text-xl font-semibold text-white">TMMS-SCPK</h1>
            <button class="text-gray-400 hover:text-white">
                <!-- Menu Icon -->
                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
          viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg> --}}
            </button>
        </div>

        <div>
            <h2 class="text-xs uppercase tracking-wide text-gray-500 mb-3"> Panel Navigasi</h2>
            @if (Auth::user()->role === 1)
                <nav class="space-y-2">

                    <!-- Active Dashboard -->
                    <a href="/home"
                        class="{{ Request::is('home') ? 'flex gap-3 bg-blue-600 px-4 py-2 rounded-lg' : 'flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 transition' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="/kriteria"
                        class="{{ Request::is('kriteria*') ? 'flex gap-3 bg-blue-600 px-4 py-2 rounded-lg' : 'flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 transition' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M12 2v5.054c0 .424 0 .837.046 1.177c.051.383.177.82.54 1.183s.8.489 1.184.54c.34.046.752.046 1.176.046H20v6c0 2.828 0 4.243-.879 5.121C18.243 22 16.828 22 14 22h-4c-2.828 0-4.243 0-5.121-.879C4 20.243 4 18.828 4 16V8c0-2.828 0-4.243.879-5.121C5.757 2 7.172 2 10 2zm2 .005V7c0 .5.002.774.028.964v.007l.008.001c.19.026.464.028.964.028h4.995c-.01-.412-.043-.684-.147-.937c-.152-.367-.441-.657-1.02-1.235l-2.656-2.656c-.578-.578-.867-.868-1.235-1.02c-.253-.105-.525-.137-.937-.147"
                                clip-rule="evenodd" />
                        </svg>
                        Data Kriteria
                    </a>

                    <!-- Menu Lain -->
                    <a href="/karyawan"
                        class="{{ Request::is('karyawan*') ? 'flex gap-3 bg-blue-600 px-4 py-2 rounded-lg' : 'flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 transition' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" width="16" height="16"
                            viewBox="0 0 16 16">
                            <path fill="currentColor"
                                d="M8 2.002a1.998 1.998 0 1 0 0 3.996a1.998 1.998 0 0 0 0-3.996M12.5 3a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3m-9 0a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3M5 7.993A1 1 0 0 1 6 7h4a1 1 0 0 1 1 1v3a3 3 0 0 1-.146.927A3.001 3.001 0 0 1 5 11zM4 8c0-.365.097-.706.268-1H2a1 1 0 0 0-1 1v2.5a2.5 2.5 0 0 0 3.436 2.319A4 4 0 0 1 4 10.999zm8 0v3c0 .655-.157 1.273-.436 1.819A2.5 2.5 0 0 0 15 10.5V8a1 1 0 0 0-1-1h-2.268c.17.294.268.635.268 1" />
                        </svg>
                        Data Karyawan
                    </a>

                    <a href="/alternatif"
                        class="{{ Request::is('alternatif*') ? 'flex gap-3 bg-blue-600 px-4 py-2 rounded-lg' : 'flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 transition' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 -w-5" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12.75 15.5a.25.25 0 0 0-.25.25v8a.25.25 0 0 0 .25.25H18a3 3 0 0 0 3-3v-5.25a.25.25 0 0 0-.25-.25ZM15 18h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1m0 2h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1m-3.5-4.25a.25.25 0 0 0-.25-.25h-8a.25.25 0 0 0-.25.25V21a3 3 0 0 0 3 3h5.25a.25.25 0 0 0 .25-.25Zm-5.85 2.6a.49.49 0 0 1 .7-.7l1 1a.27.27 0 0 0 .36 0l1-1a.49.49 0 0 1 .7.7l-1 1a.27.27 0 0 0 0 .36l1 1a.48.48 0 0 1 0 .7a.48.48 0 0 1-.7 0l-1-1a.27.27 0 0 0-.36 0l-1 1a.48.48 0 0 1-.7 0a.48.48 0 0 1 0-.7l1-1a.27.27 0 0 0 0-.36ZM16 5.75a.76.76 0 0 0 .75-.75V3.5a.75.75 0 0 0-1.5 0V5a.76.76 0 0 0 .75.75" />
                            <path fill="currentColor"
                                d="M18 0H6a3 3 0 0 0-3 3v11.25a.25.25 0 0 0 .25.25h8a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25h-6A.25.25 0 0 1 5 6.25V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3.25a.25.25 0 0 1-.25.25h-6a.25.25 0 0 0-.25.25v7.5a.25.25 0 0 0 .25.25h8a.25.25 0 0 0 .25-.25V3a3 3 0 0 0-3-3M9 11.5h-.75a.25.25 0 0 0-.25.25v.75a.5.5 0 0 1-1 0v-.75a.25.25 0 0 0-.25-.25H6a.5.5 0 0 1 0-1h.75a.25.25 0 0 0 .25-.25V9.5a.5.5 0 0 1 1 0v.75a.25.25 0 0 0 .25.25H9a.5.5 0 0 1 0 1m9 0h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 1 0 1" />
                        </svg>
                        Alternatif
                    </a>

                    <a href="/hasil-akhir"
                        class="{{ Request::is('hasil-akhir*') ? 'flex gap-3 bg-blue-600 px-4 py-2 rounded-lg' : 'flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 transition' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" width="256" height="256"
                            viewBox="0 0 256 256">
                            <path fill="currentColor"
                                d="M240 200h-8v-56a16 16 0 0 0-16-16h-40V56a16 16 0 0 0-16-16H96a16 16 0 0 0-16 16v32H40a16 16 0 0 0-16 16v96h-8a8 8 0 0 0 0 16h224a8 8 0 0 0 0-16m-160 0H40v-96h40Zm60-64a8 8 0 0 1-16 0v-28.9l-1.47.49a8 8 0 0 1-5.06-15.18l12-4A8 8 0 0 1 140 96Zm76 64h-40v-56h40Z" />
                        </svg>
                        Hasil Akhir
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-red-800/50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" width="24" height="24"
                            viewBox="0 0 24 24">
                            <g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd">
                                <path
                                    d="M19.353 6.5H16.49V9H6.404v6H16.49v2.5h2.864A9.99 9.99 0 0 1 11 22C5.477 22 1 17.523 1 12S5.477 2 11 2a9.99 9.99 0 0 1 8.353 4.5M17.989 16v-1zm0-8v1z" />
                                <path d="m18.99 8l4 4l-4 4h-1v-2.5h-10v-3h10V8z" />
                            </g>
                        </svg>
                        Logout
                    </a>
                </nav>
            @else
                <nav class="space-y-2">

                    <!-- Active Dashboard -->
                    <a href="/home1"
                        class="{{ Request::is('home1') ? 'flex gap-3 bg-blue-600 px-4 py-2 rounded-lg' : 'flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 transition' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z" />
                        </svg>
                        Dashboard
                    </a>

                    <a href="/kriteria1"
                        class="{{ Request::is('kriteria1*') ? 'flex gap-3 bg-blue-600 px-4 py-2 rounded-lg' : 'flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 transition' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path fill="currentColor" fill-rule="evenodd"
                                d="M12 2v5.054c0 .424 0 .837.046 1.177c.051.383.177.82.54 1.183s.8.489 1.184.54c.34.046.752.046 1.176.046H20v6c0 2.828 0 4.243-.879 5.121C18.243 22 16.828 22 14 22h-4c-2.828 0-4.243 0-5.121-.879C4 20.243 4 18.828 4 16V8c0-2.828 0-4.243.879-5.121C5.757 2 7.172 2 10 2zm2 .005V7c0 .5.002.774.028.964v.007l.008.001c.19.026.464.028.964.028h4.995c-.01-.412-.043-.684-.147-.937c-.152-.367-.441-.657-1.02-1.235l-2.656-2.656c-.578-.578-.867-.868-1.235-1.02c-.253-.105-.525-.137-.937-.147"
                                clip-rule="evenodd" />
                        </svg>
                        Data Kriteria
                    </a>

                    <!-- Menu Lain -->
                    <a href="/karyawan1"
                        class="{{ Request::is('karyawan1*') ? 'flex gap-3 bg-blue-600 px-4 py-2 rounded-lg' : 'flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 transition' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" width="16" height="16"
                            viewBox="0 0 16 16">
                            <path fill="currentColor"
                                d="M8 2.002a1.998 1.998 0 1 0 0 3.996a1.998 1.998 0 0 0 0-3.996M12.5 3a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3m-9 0a1.5 1.5 0 1 0 0 3a1.5 1.5 0 0 0 0-3M5 7.993A1 1 0 0 1 6 7h4a1 1 0 0 1 1 1v3a3 3 0 0 1-.146.927A3.001 3.001 0 0 1 5 11zM4 8c0-.365.097-.706.268-1H2a1 1 0 0 0-1 1v2.5a2.5 2.5 0 0 0 3.436 2.319A4 4 0 0 1 4 10.999zm8 0v3c0 .655-.157 1.273-.436 1.819A2.5 2.5 0 0 0 15 10.5V8a1 1 0 0 0-1-1h-2.268c.17.294.268.635.268 1" />
                        </svg>
                        Data Karyawan
                    </a>

                    <a href="/alternatif1"
                        class="{{ Request::is('alternatif1*') ? 'flex gap-3 bg-blue-600 px-4 py-2 rounded-lg' : 'flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 transition' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 -w-5" width="24" height="24"
                            viewBox="0 0 24 24">
                            <path fill="currentColor"
                                d="M12.75 15.5a.25.25 0 0 0-.25.25v8a.25.25 0 0 0 .25.25H18a3 3 0 0 0 3-3v-5.25a.25.25 0 0 0-.25-.25ZM15 18h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1m0 2h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1m-3.5-4.25a.25.25 0 0 0-.25-.25h-8a.25.25 0 0 0-.25.25V21a3 3 0 0 0 3 3h5.25a.25.25 0 0 0 .25-.25Zm-5.85 2.6a.49.49 0 0 1 .7-.7l1 1a.27.27 0 0 0 .36 0l1-1a.49.49 0 0 1 .7.7l-1 1a.27.27 0 0 0 0 .36l1 1a.48.48 0 0 1 0 .7a.48.48 0 0 1-.7 0l-1-1a.27.27 0 0 0-.36 0l-1 1a.48.48 0 0 1-.7 0a.48.48 0 0 1 0-.7l1-1a.27.27 0 0 0 0-.36ZM16 5.75a.76.76 0 0 0 .75-.75V3.5a.75.75 0 0 0-1.5 0V5a.76.76 0 0 0 .75.75" />
                            <path fill="currentColor"
                                d="M18 0H6a3 3 0 0 0-3 3v11.25a.25.25 0 0 0 .25.25h8a.25.25 0 0 0 .25-.25v-7.5a.25.25 0 0 0-.25-.25h-6A.25.25 0 0 1 5 6.25V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3.25a.25.25 0 0 1-.25.25h-6a.25.25 0 0 0-.25.25v7.5a.25.25 0 0 0 .25.25h8a.25.25 0 0 0 .25-.25V3a3 3 0 0 0-3-3M9 11.5h-.75a.25.25 0 0 0-.25.25v.75a.5.5 0 0 1-1 0v-.75a.25.25 0 0 0-.25-.25H6a.5.5 0 0 1 0-1h.75a.25.25 0 0 0 .25-.25V9.5a.5.5 0 0 1 1 0v.75a.25.25 0 0 0 .25.25H9a.5.5 0 0 1 0 1m9 0h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 1 0 1" />
                        </svg>
                        Alternatif
                    </a>

                    <a href="/hasil-akhir1"
                        class="{{ Request::is('hasil-akhir1*') ? 'flex gap-3 bg-blue-600 px-4 py-2 rounded-lg' : 'flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-800 transition' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" width="256" height="256"
                            viewBox="0 0 256 256">
                            <path fill="currentColor"
                                d="M240 200h-8v-56a16 16 0 0 0-16-16h-40V56a16 16 0 0 0-16-16H96a16 16 0 0 0-16 16v32H40a16 16 0 0 0-16 16v96h-8a8 8 0 0 0 0 16h224a8 8 0 0 0 0-16m-160 0H40v-96h40Zm60-64a8 8 0 0 1-16 0v-28.9l-1.47.49a8 8 0 0 1-5.06-15.18l12-4A8 8 0 0 1 140 96Zm76 64h-40v-56h40Z" />
                        </svg>
                        Hasil Akhir
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-red-800/50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" width="24" height="24"
                            viewBox="0 0 24 24">
                            <g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd">
                                <path
                                    d="M19.353 6.5H16.49V9H6.404v6H16.49v2.5h2.864A9.99 9.99 0 0 1 11 22C5.477 22 1 17.523 1 12S5.477 2 11 2a9.99 9.99 0 0 1 8.353 4.5M17.989 16v-1zm0-8v1z" />
                                <path d="m18.99 8l4 4l-4 4h-1v-2.5h-10v-3h10V8z" />
                            </g>
                        </svg>
                        Logout
                    </a>

                </nav>
            @endif
        </div>
    </aside>

</body>

</html>
