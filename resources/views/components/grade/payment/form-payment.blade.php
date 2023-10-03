@extends('layouts.admin.master')
@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <!-- left column -->
            <div class="col-md-6">
                <!-- general form elements -->
                <div>
                    <form method="POST" action={{route('create.paymentGrade', $data->id)}}>
                        @csrf
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Create payment static for {{$data->name}} {{$data->class}}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <label for="type">Type<span style="color: red">*</span></label>
                                        <select required name="type" class="form-control" id="type">
                                            <option selected disabled>--- SELECT TYPE ---</option>
                                            <option>SPP</option>
                                            <option>Uniform</option>
                                            <option>Entrance Fee</option>
                                            <option>Book</option>
                                            <option>Other</option>
                                        </select>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('type')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                       
                                       <label for="amount">Amount<span style="color: red">*</span></label>
                                       <div class="input-group">
                                          <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input name="amount" type="text" class="form-control" id="amount"
                                            placeholder="Enter amount" value="{{old('amount')}}" required>
                                        <div class="input-group-append">
                                           <span class="input-group-text">.00</span>
                                          </div>
                                       </div>
                          
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('amount')}}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="row d-flex justify-content-center">
                                    <input role="button" type="submit" class="btn btn-success center col-11 m-3">
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Mendapatkan elemen input
    const input = document.getElementById("amount");

    // Menambahkan event listener pada input saat pengguna mengetik
    input.addEventListener("input", function () {
        // Mengambil nilai input tanpa tanda titik dan karakter non-angka
        const rawValue = input.value.replace(/[^0-9]/g, '');

        // Mengubah nilai input dengan menambahkan tanda titik setiap 3 digit
        const formattedValue = addThousandSeparator(rawValue);

        // Memasukkan nilai yang telah diformat kembali ke dalam input
        input.value = formattedValue;
    });

    // Fungsi untuk menambahkan tanda titik sebagai pemisah ribuan
    function addThousandSeparator(value) {
        return value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

</script>

@endsection
