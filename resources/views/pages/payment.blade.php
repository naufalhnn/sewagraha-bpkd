@extends('layouts.home')

@section('content')
		<section class="mx-auto max-w-4xl px-4 pb-20">
				<div class="text-primary my-5 text-center text-3xl font-bold">
						<h1>Konfirmasi Pembayaran</h1>
				</div>

				<div class="rounded-xl bg-white p-6 shadow-lg">
						<h2 class="mb-4 text-2xl font-semibold">Detail Pembayaran</h2>
						<p class="text-gray-700">Total Harga: <strong>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</strong></p>
						<p class="text-gray-700">Silakan transfer ke rekening berikut:</p>
						<p class="font-semibold text-gray-800">Bank XYZ - 1234567890 - A/N BPKD Kabupaten Pekalongan</p>

						<form action="" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
								@csrf
								<input type="hidden" name="booking_id" value="{{ $booking->id }}">

								<div>
										<label class="mb-1 block text-sm font-medium text-gray-700">Unggah Bukti Transfer</label>
										<input type="file"
												class="text-stone-500 file:mr-5 file:border-[1px] file:bg-stone-50 file:px-3 file:py-1 file:text-xs file:font-medium file:text-stone-700 hover:file:cursor-pointer hover:file:bg-blue-50 hover:file:text-blue-700" />
								</div>

								<button type="submit" class="w-full cursor-pointer rounded-lg bg-blue-600 py-2 text-white hover:bg-blue-700">
										Konfirmasi Pembayaran
								</button>
						</form>
				</div>
		</section>
@endsection
