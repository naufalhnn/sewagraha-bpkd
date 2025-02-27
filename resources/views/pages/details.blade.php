@extends('layouts.home')

@section('content')
		<section class="mx-auto max-w-6xl px-4 pb-20">
				<div class="text-primary my-5 text-3xl font-bold">
						<h1>{{ $venue->name }}</h1>
				</div>

				<div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
						<div class="h-1/2 overflow-hidden rounded-xl">
								<div class="h-full w-full">
										<div id="slider" class="relative h-full w-full">
												<div class="slider-items flex h-full transition-transform duration-300">
														@foreach ($venue->venueImages as $image)
																<img src="{{ asset('storage/' . $image->image_path) }}" alt="Gambar Gedung"
																		class="h-full w-full object-cover">
														@endforeach
												</div>

												<button id="prevBtn"
														class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-2 hover:bg-white">
														<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
																stroke="currentColor">
																<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
														</svg>
												</button>
												<button id="nextBtn"
														class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-white/80 p-2 hover:bg-white">
														<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
																stroke="currentColor">
																<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
														</svg>
												</button>
										</div>
								</div>
								<div class="absolute w-1/3">
										<h2 class="my-4 text-xl font-semibold">Deskripsi Gedung</h2>
										<div class="flex flex-col gap-2">
												<p class="text-base font-normal text-gray-700">{{ $venue->description }}</p>
												<p class="text-base font-normal text-gray-700">Alamat: {{ $venue->address }}</p>
												<p class="text-base font-normal text-gray-700">Kapasitas: {{ $venue->capacity }}</p>
												<div class="flex flex-row space-x-6">
														<p class="text-base font-normal text-gray-700">Kegunaan:</p>
														<ul style="list-style-type: disc">
																@foreach ($venue->purposes as $purpose)
																		<li class="text-base font-normal text-gray-700">{{ $purpose->name }}</li>
																@endforeach
														</ul>
												</div>
												<div class="flex flex-row space-x-6">
														<p class="text-base font-normal text-gray-700">Fasilitas:</p>
														<ul style="list-style-type: disc">
																@foreach ($venue->facilities as $facility)
																		<li class="text-base font-normal text-gray-700">{{ $facility->name }}</li>
																@endforeach
														</ul>
												</div>

												<p class="text-base font-normal text-gray-700">Kondisi Bangunan: {{ $venue->building_condition }}</p>
												<p class="text-base font-normal text-gray-700">Harga Per Hari: Rp.
														{{ number_format($venue->base_price, 0, ',', '.') }}</p>

												<p class="text-base font-normal text-gray-700">Untuk pemesanan jangka panjang, silahkan hubungi kami melalui
														nomor telepon yang tertera di halaman kontak.
												</p>

										</div>
								</div>

						</div>
						<div class="rounded-xl bg-white p-6 shadow-lg">
								<h2 class="mb-4 text-2xl font-semibold">Pesan Gedung</h2>
								<form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
										@csrf
										<input type="hidden" name="venue_id" value="{{ $venue->id }}">
										<input type="hidden" name="user_id" value="{{ auth()->id() }}">

										<div>
												<label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
												<input type="text" name="name" class="mt-1 w-full rounded-lg border border-gray-300 p-2"
														value="{{ auth()->user()->name }}" required>
										</div>

										<div>
												<label class="block text-sm font-medium text-gray-700">Email</label>
												<input type="email" name="email" class="mt-1 w-full rounded-lg border border-gray-300 p-2"
														value="{{ auth()->user()->email }}" required>
										</div>

										<div>
												<label class="block text-sm font-medium text-gray-700">Tanggal Mulai Acara</label>
												<input type="date" name="event_start_date" id="event_start_date"
														class="mt-1 w-full rounded-lg border border-gray-300 p-2" required>
										</div>

										<div>
												<label class="block text-sm font-medium text-gray-700">Tanggal Selesai Acara</label>
												<input type="date" name="event_end_date" id="event_end_date"
														class="mt-1 w-full rounded-lg border border-gray-300 p-2" required>
										</div>

										<div>
												<label class="block text-sm font-medium text-gray-700">Keperluan</label>
												<textarea name="purpose" rows="3" class="mt-1 w-full rounded-lg border border-gray-300 p-2" required></textarea>
										</div>

										<div>
												<label class="block text-sm font-medium text-gray-700">Total Harga</label>
												<input type="text" name="total_price" id="total_price"
														class="mt-1 w-full rounded-lg border border-gray-300 bg-gray-100 p-2" readonly>
										</div>

										<button type="submit" class="w-full cursor-pointer rounded-lg bg-blue-600 py-2 text-white hover:bg-blue-700">
												Pesan Sekarang
										</button>
								</form>
						</div>
				</div>
		</section>
@endsection
