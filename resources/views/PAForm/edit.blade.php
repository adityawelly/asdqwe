@extends('layouts.app')

@section('content')
<div class="content animated fadeIn">
    <div class="page-inner">
        <div class="page-header">
            <h4 class="page-title">Penilaian Karyawan</h4>
            {{ Breadcrumbs::render('PAFORM') }}
        </div>
        <div class="row">
            <div class="col-md-12">
                @include('layouts.partials.alert')
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h4 class="card-title">Form Penilaian Karyawan</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('PAForm.submit_edit') }}" method="POST" enctype="multipart/form-data">
                            @csrf
							<input type="hidden" name="PaId" value="{{ $pengajuan->PaId }}">
							<input type="hidden" name="PaPeriodId" value="{{ $pengajuan->PaPeriodId }}">
							<input type="hidden" name="EmpId" value="{{ $pengajuan->EmployeeId }}">
                            <div class="row">
								@foreach ($edited as $edit)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Nama Karyawan <span class="required-label">*</span></label>
										<input type="text" class="form-control" value="{{ $edit->employee_id .' - '.$edit->fullname }}" disabled>

                                    </div>
                                    <div class="form-group">
                                        <label for="">Jabatan</label>
										<input type="text" class="form-control" value="{{ $edit->job_title_name }}" disabled>
										<input type="hidden" name="grade" value="{{ $edit->grade_title_id }}">
                                    </div>
								</div>
								<div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Departement</label>
										<input type="text" class="form-control" value="{{ $edit->department_name }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Nama Penilai</label>
										<input type="text" class="form-control" value="{{ $edit->nama_atasan }}" disabled>
                                    </div>
                                </div>
								@endforeach
								<div class="col-md-12">
                                    <div class="form-group">
                                      <table><tr><td>
									  PETUNJUK PENGISIAN: Berilah nilai untuk masing-masing kriteria yang telah ditetapkan (skala 1 - 5) dengan mencocokkan hasil yang ditunjukkan bawahan di setiap kriteria tersebut dengan definisi yang tersedia pada masing-masing kotak.
									  Kemudian tentukan satu nilai dalam range nilai yang ada yang dianggap sesuai untuk hasil yang ditunjukkan oleh bawahan. Diskusikan dengan bawahan dan sepakati bersama.
									  Kemudian teruskan ke atasan yang lebih tinggi untuk diketahui, dan seterusnya diserahkan ke HCM di unit masing2 untuk dibuat rekapitulasinya.
									  </td></tr></table>
                                    </div>
								</div> <br>


								<div class="col-md-12">
									<div class="form-group">
								        <label for="">HASIL PENILAIAN KARYAWAN<span class="required-label">*</span></label>
										<div class="row">
										<label for="">A. KEMAMPUAN</label>
										<table width="100%" class="table table-bordered" >
										<tr>
											<td width="10%">BOBOT</td>
											<td width="10%">PARAMETER</td>
											<td width="63%"></td>
											<td width="12%">NILAI</td>
										</tr>
										@foreach($inserted as $key => $item )
										<tr id="tablean">
											@php
                                                $subbab = DB::select("select * from pa_params where SubbabId = $item->PaParamsId");
                                            @endphp
											<td><input type="number" class="form-control float-bobot" id="bobot{{$key}}" name="PaParamsBobot[]" value="{{ $item->PaParamsBobot }}" readonly></td>
											<td><input type="hidden" class="form-control" name="PaParamsId[]" value="{{ $item->PaParamsId }}" readonly>{{ $item->Namasub }}</td>
											<td>
											<table class="table table-borderless">
												@foreach ($subbab as $items)
												<tr>
													<td>{{ $items->Parameters }}</td>
													<td>{{ $items->Nilai }}</td>
												</tr>
												@endforeach
											</table>
											</td>
											<td>
												<input type="hidden" class="form-control float-total" id="gt{{$key}}" name="gt[]" value="0" readonly>
												<input type="text" class="form-control float-nominal" onblur="return calc_bal('bobot{{$key}}','param{{$key}}','gt{{$key}}')" id="param{{$key}}" name="PaParamsScore[]" value="{{ $item->PaParamsScore }}" placeholder="0">
											</td>
										</tr>
										@endforeach
										<tr><td colspan="3">Total Nilai</td><td><input type="number" id="total" class="form-control total" name="total" value="{{ number_format($pengajuan->PaScore,2) }}" readonly></td></tr>
										@if($edit->grade_title_id != 5)
											<tr><td colspan="3">Total KPI</td><td><input type="text" id="totalan" class="form-control totalan" name="kpi" value="0" maxlength="4" required></td></tr>
											<tr><td colspan="3">Total</td><td><input type="number" id="skor" class="form-control skor" name="skor" value="0" readonly></td></tr>
										@endif
										</table>
										<div>Range: <br> 4.01 - 5.00 = A <br> 3.01 - 4.00 = B <br> 2.01 - 3.00 = C <br> 1.01 - 2.00 = D <br> 0.00 - 1.00 = E</div> <br>
										</div>
									</div>
								</div>


									<div class="col-md-12">
										<div class="form-group">
											<label for="">Catatan</label>
											<textarea name="Notes" cols="30" rows="4" value="{{ $pengajuan->Notes }}" class="form-control">{{ $pengajuan->Notes }}</textarea>
										</div>
									</div>

                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-md pull-right"><i class="fas fa-save"></i>Update<button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
<script>

	$(document).on("change", ".totalan", function() {
		var tkp = 0;
		var kpi = $(".totalan").val();
		var tot = $(".total").val();
		var tkp = (kpi * 1) + (tot * 1);
		$(".skor").val(tkp.toFixed(2));
	});

	function calc_bal(id1,id2,id3)
	{
		var rqty = $("#"+id1).val();
		var sqty = $("#"+id2).val();
		var bal = (rqty * 1) * (sqty * 1 )/100;

		$("#"+id3).val(bal);

		total_all_kolom_total();
	}

	function total_all_kolom_total()
	{
		var sum = 0;
		$(".float-total").each(function(){
			var num = $(this).val();
			sum += (+num * 1);
		});

		$(".total").val(sum.toFixed(2));

	}

	//$('.float-nominal').mask("^[1-5]?$|^10$", {reverse: true});
	$('.float-nominal').mask('Y', {'translation': {
		Y: {pattern: /[1-5]/}
		}
	});

	// $('.totalan').mask('0.00', { min:0, max:2, reverse: true});

    $('.totalan')
	.keydown(function (e) {
		var key = e.which || e.charCode || e.keyCode || 0;
		$phone = $(this);

		// Auto-format- do not expose the mask as the user begins to type

		if (key !== 8 && key !== 9) {
    	    if ($phone.val().length === 0) {
      	        if (key >= 48 && key <= 50) {
                    return true;
                } else {
                    return false;
                }
			}

			if ($phone.val().length === 1) {
				$phone.val($phone.val() + '.');
			}

			if ($phone.val().length === 2) {
      	        if ($phone.val() == 2) {
                    if (key >= 48 && key <= 53) {
                        return true;
                    } else {
                        return false;
                    }
                }
			}

            if ($phone.val().length === 3) {
                if ($phone.val() == 2.5) {
                    if (key === 48) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
		}

		// Allow numeric (and tab, backspace, delete) keys only
		return (key == 8 || key == 9 || key == 46 || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
	})

	$('.datepicker').datetimepicker({
    format: 'DD-MM-YYYY'
	});

	$('.Gapok_baru').mask('000.000.000', {reverse: true});

	$(function()
    {
      $('[id="hal1"]').change(function()
      {
        if ($(this).is(':checked')) {
           // Do something...
           //alert('You can rock now...');
		   $("#formhpk").show();
        }
		else
		{
			$("#formhpk").hide();
		};

      });
	  $('[id="hal4"]').change(function()
      {
        if ($(this).is(':checked')) {
           // Do something...
           //alert('You can rock now...');
		   $("#formhpk").show();
        }
		else
		{
			$("#formhpk").hide();
		};

      });
	  $('[id="hal5"]').change(function()
      {
        if ($(this).is(':checked')) {
		   $("#formhpk").show();
		   $("#kontrak").show();
        }
		else
		{
			$("#formhpk").hide();
			$("#kontrak").hide();
		};

      });
	  $('[id="hal7"]').change(function()
      {
      if ($(this).is(':checked')) {
		   $("#formhpk").show();
        }
		else
		{
			$("#formhpk").hide();
		};

      });
	  	  $('[id="hal8"]').change(function()
      {
        if ($(this).is(':checked')) {
		   $("#formhpk").show();
        }
		else
		{
			$("#formhpk").hide();
		};

      });
    });


	$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#Departement_baru').on('change', function () {
        $.ajax({
            url: '{{ route('pengajuan.fpk.get_dept_list') }}',
            method: 'POST',
            data: {id: $(this).val()},
            success: function (response) {
                $('#Jabatan_baru').empty();

                $.each(response, function (id , name) {
                    $('#Jabatan_baru').append('<option value="' +id+ '">' +id+ '-'+ name +'</option>');
                })
            }
        })
    });
	});


	$(function () {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $('#Jabatan_baru').on('change', function () {
        $.ajax({
            url: '{{ route('pengajuan.fpk.get_atasan_list') }}',
            method: 'POST',
            data: {id: $(this).val()},
            success: function (response) {
                $('#Atasan_baru').empty();

                $.each(response, function (id, name, level) {
                    $('#Atasan_baru').append('<option value="'+id+'">'+name+'</option>');
                })
            }
        })
    });
	});


    $('form').on('submit', function(){
        $(this).find('button[type=submit]').attr('disabled', true).addClass('is-loading');
    });

</script>
@endsection
