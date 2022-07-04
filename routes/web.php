<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return route('login');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'testing'], function () {
    Route::get('checkyear', 'TestingController@checkYear');
});

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'DashboardController@index');
    Route::get('/events', 'DashboardController@events');

    Route::group(['prefix' => 'employee'], function () {
        Route::post('import', 'EmployeeController@import')->name('employee.import')->middleware('permission:import-employee');
        Route::post('import_rek', 'EmployeeController@import_rek')->name('employee.import_rek')->middleware('permission:import-employee');
		Route::post('datatable', 'EmployeeController@datatable')->name('employee.datatable')->middleware('permission:read-employee');
        Route::post('restore/{id}', 'EmployeeController@restore')->name('employee.restore')->middleware('permission:restore-employee');
        Route::get('export/excel', 'EmployeeController@excel')->name('employee.excel')->middleware('permission:export-employee');
        Route::get('export/csv', 'EmployeeController@csv')->name('employee.csv')->middleware('permission:export-employee');
        Route::get('export/pdf', 'EmployeeController@pdf')->name('employee.pdf')->middleware('permission:export-employee');
        Route::post('resign', 'EmployeeRetirementController@resign')->name('employee.resign')->middleware('permission:create-resign');

        Route::group(['prefix' => 'retirement'], function () {
            Route::get('/', 'EmployeeRetirementController@index')->name('employee.retirement')->middleware('permission:read-resign');
            Route::get('/excel', 'EmployeeRetirementController@excel')->name('employee.retirement_excel')->middleware('permission:read-resign');
            Route::get('/{id}', 'EmployeeRetirementController@show')->name('employee.retirement_show')->middleware('permission:read-resign');
            Route::put('/{id}', 'EmployeeRetirementController@update')->name('employee.retirement_update')->middleware('permission:update-resign');
            Route::delete('/{id}', 'EmployeeRetirementController@destroy')->name('employee.retirement_destroy')->middleware('permission:delete-resign');
        });

        Route::get('/', 'EmployeeController@index')->name('employee.index')->middleware('permission:read-employee');
        Route::get('/create', 'EmployeeController@create')->name('employee.create')->middleware('permission:create-employee');
        Route::post('/', 'EmployeeController@store')->name('employee.store')->middleware('permission:create-employee');
        Route::get('/{id}', 'EmployeeController@show')->name('employee.show')->middleware('permission:read-employee');
        Route::get('/{id}/edit', 'EmployeeController@edit')->name('employee.edit')->middleware('permission:update-employee');
        Route::put('/{employee}', 'EmployeeController@update')->name('employee.update')->middleware('permission:update-employee');
        Route::delete('/{id}', 'EmployeeController@destroy')->name('employee.destroy')->middleware('permission:delete-employee');

        Route::post('/get_teams', 'EmployeeController@get_teams')->name('employee.get_teams')->middleware('permission:read-employee');
        Route::get('/get_direct_superior/{id}', 'EmployeeController@get_direct_superior')->name('employee.get_direct_superior')->middleware('permission:read-employee');
        Route::post('/employee_select_data', 'EmployeeController@employee_select_data')->name('employee.employee_select_data');
        Route::post('/update_direct_superior', 'EmployeeController@update_direct_superior')->name('employee.update_direct_superior')->middleware('permission:update-employee');
    });
    // Route::resource('employee', 'EmployeeController');

    Route::group(['prefix' => 'division'], function () {
        Route::post('datatable', 'DivisionController@datatable')->name('division.datatable')->middleware('permission:read-division');
        Route::post('restore/{id}', 'DivisionController@restore')->name('division.restore')->middleware('permission:restore-division');
        Route::get('export/excel', 'DivisionController@excel')->name('division.excel')->middleware('permission:export-division');
        Route::get('export/csv', 'DivisionController@csv')->name('division.csv')->middleware('permission:export-division');
        Route::get('export/pdf', 'DivisionController@pdf')->name('division.pdf')->middleware('permission:export-division');
        Route::get('get_departments/{division}', 'DivisionController@get_departments')->name('division.departments')->middleware('permission:read-division');

        Route::get('/', 'DivisionController@index')->name('division.index')->middleware('permission:read-division');
        Route::post('/', 'DivisionController@store')->name('division.store')->middleware('permission:create-division');
        Route::get('/{id}', 'DivisionController@show')->name('division.show')->middleware('permission:read-division');
        Route::put('/{division}', 'DivisionController@update')->name('division.update')->middleware('permission:update-division');
        Route::delete('/{id}', 'DivisionController@destroy')->name('division.destroy')->middleware('permission:delete-division');
    });
    // Route::resource('division', 'DivisionController');

	Route::group(['prefix' => 'faq'], function () {
        Route::post('datatable', 'DivisionController@datatable')->name('division.datatable')->middleware('permission:read-faq');

        Route::get('/', 'FAQController@index')->name('faq.index')->middleware('permission:read-faq');
        Route::post('/', 'FAQController@store')->name('faq.store')->middleware('permission:create-faq');
        Route::get('/{id}', 'FAQController@show')->name('faq.show')->middleware('permission:read-faq');
        Route::put('/{faq}', 'FAQController@update')->name('faq.update')->middleware('permission:update-faq');
        Route::delete('/{id}', 'FAQController@destroy')->name('faq.destroy')->middleware('permission:delete-faq');
    });

	// Route::resource('faq', 'FAQController');

    Route::group(['prefix' => 'leave'], function () {
        Route::group(['prefix' => 'quota'], function () {
            Route::get('/', 'LeaveController@quota_index')->name('leave.quota_index');
            Route::post('quota_import', 'LeaveController@quota_import')->name('leave.quota_import')->middleware('permission:create-leave', 'role:Personnel');
            Route::post('quota_import_save', 'LeaveController@quota_import_save')->name('leave.quota_import_save')->middleware('permission:create-leave', 'role:Personnel');
            Route::get('quota_import_export', 'LeaveController@quota_export')->name('leave.quota_export')->middleware('permission:create-leave', 'role:Personnel');
        });

        Route::group(['prefix' => 'extend'], function () {
            Route::post('submit_extend', 'LeaveController@submit_extend')->name('leave.submit_extend')->middleware('permission:create-leave', 'role:Personnel');
        });

        Route::group(['prefix' => 'opname'], function () {
            Route::get('/', 'LeaveController@opname_index')->name('leave.opname_index');
            Route::post('/update', 'LeaveController@opname_update')->name('leave.opname_update');
            Route::get('/export', 'LeaveController@opname_export')->name('leave.opname_export');
        });

        Route::post('datatable', 'LeaveController@datatable')->name('leave.datatable')->middleware('permission:read-leave');
        Route::post('restore/{id}', 'LeaveController@restore')->name('leave.restore')->middleware('permission:restore-leave');
        Route::get('export/excel', 'LeaveController@excel')->name('leave.excel')->middleware('permission:export-leave');
        Route::get('export/csv', 'LeaveController@csv')->name('leave.csv')->middleware('permission:export-leave');
        Route::get('export/pdf', 'LeaveController@pdf')->name('leave.pdf')->middleware('permission:export-leave');

        Route::get('/', 'LeaveController@index')->name('leave.index')->middleware('permission:read-leave');
        Route::post('/', 'LeaveController@store')->name('leave.store')->middleware('permission:create-leave');
        Route::put('/set_annual', 'LeaveController@set_annual')->name('leave.set_annual')->middleware('permission:set-annual-leave');
        Route::get('/{leave}', 'LeaveController@show')->name('leave.show')->middleware('permission:read-leave');
        Route::put('/{leave}', 'LeaveController@update')->name('leave.update')->middleware('permission:update-leave');
        Route::delete('/{leave}', 'LeaveController@destroy')->name('leave.destroy')->middleware('permission:delete-leave');
    });

    Route::group(['prefix' => 'training'], function () {
        Route::post('import', 'TrainingController@import')->name('training.import')->middleware('permission:import-training');
        Route::post('participants', 'TrainingController@participants')->name('training.participants')->middleware('permission:read-training');
        Route::post('restore/{id}', 'TrainingController@restore')->name('training.restore')->middleware('permission:restore-training');
        Route::get('export/excel', 'TrainingController@excel')->name('training.excel')->middleware('permission:export-training');
        Route::get('export/csv', 'TrainingController@csv')->name('training.csv')->middleware('permission:export-training');
        Route::get('export/pdf', 'TrainingController@pdf')->name('training.pdf')->middleware('permission:export-training');

        Route::get('/', 'TrainingController@index')->name('training.index')->middleware('permission:read-training');
        Route::post('/', 'TrainingController@store')->name('training.store')->middleware('permission:create-training');
        Route::put('/set_annual', 'TrainingController@set_annual')->name('training.set_annual')->middleware('permission:set-annual-training');
        Route::get('/{training}', 'TrainingController@show')->name('training.show')->middleware('permission:read-training');
        Route::put('/{training}', 'TrainingController@update')->name('training.update')->middleware('permission:update-training');
        Route::delete('/{training}', 'TrainingController@destroy')->name('training.destroy')->middleware('permission:delete-training');
    });

    Route::group(['prefix' => 'employee-leave'], function () {
        Route::get('/', 'EmployeeLeaveController@index')->name('employee-leave.index')->middleware('permission:read-employee-leave');
        Route::get('/calculate', 'EmployeeLeaveController@calculate')->name('employee-leave.calculate')->middleware('permission:create-employee-leave');
		Route::get('/calculate_direct', 'EmployeeLeaveController@calculate_direct')->name('employee-leave.calculate_direct')->middleware('permission:create-employee-leave');
        Route::get('/approval', 'EmployeeLeaveController@approval')->name('employee-leave.approval')->middleware('approval');
        Route::get('/load_quota_cuti', 'EmployeeLeaveController@load_quota_cuti')->name('employee-leave.load_quota_cuti');

        Route::post('/cuti_upload', 'EmployeeLeaveController@cuti_upload')->name('employee-leave.cuti_upload')->middleware('role:Personnel');
        Route::post('/cuti_upload_do', 'EmployeeLeaveController@cuti_upload_do')->name('employee-leave.cuti_upload_do')->middleware('role:Personnel');

		Route::get('list_data', 'EmployeeLeaveController@list_data')->name('employee-leave.list_data');

        Route::post('/{employee_leave}/approve', 'EmployeeLeaveController@approve')->name('employee-leave.approve')->middleware('approval');
        Route::get('/create/{type}', 'EmployeeLeaveController@create')->name('employee-leave.create')->middleware('permission:create-employee-leave');
        Route::post('/', 'EmployeeLeaveController@store')->name('employee-leave.store')->middleware('permission:create-employee-leave');
        Route::get('/{employee_leave}', 'EmployeeLeaveController@show')->name('employee-leave.show')->middleware('permission:read-employee-leave');
        Route::get('/{employee_leave}/edit', 'EmployeeLeaveController@edit')->name('employee-leave.edit')->middleware('permission:update-employee-leave');
        Route::put('/{employee_leave}', 'EmployeeLeaveController@update')->name('employee-leave.update')->middleware('permission:update-employee-leave');
        Route::delete('/{employee_leave}', 'EmployeeLeaveController@destroy')->name('employee-leave.destroy')->middleware('permission:delete-employee-leave');
    });

	//izin dinas luar
	 Route::group(['prefix' => 'employee-dinas'], function () {
			Route::get('/', 'EmployeeDinasController@index')->name('employee-dinas.index')->middleware('permission:create-employee-dinas');
            Route::get('/create', 'EmployeeDinasController@create')->name('employee-dinas.create')->middleware('permission:create-employee-dinas');
			Route::get('/cetak/{id}', 'EmployeeDinasController@cetak')->name('employee-dinas.cetak');
			Route::get('/create_direct', 'EmployeeDinasController@create_direct')->name('employee-dinas.create_direct')->middleware('permission:create-employee-dinas');
			Route::post('/store', 'EmployeeDinasController@store')->name('employee-dinas.store')->middleware('permission:create-employee-dinas');
			Route::post('/', 'EmployeeDinasController@store_direct')->name('employee-dinas.store_direct')->middleware('permission:create-employee-dinas');
			Route::get('/approval', 'EmployeeDinasController@approval')->name('employee-dinas.approval');
			Route::post('/{employee_dinas}/approve', 'EmployeeDinasController@approve')->name('employee-dinas.approve')->middleware('approval');
			Route::get('/edit/{id}', 'EmployeeDinasController@edit')->name('employee-dinas.edit');
			Route::post('/update', 'EmployeeDinasController@update')->name('employee-dinas.update');
        });

	//izin isoman
	Route::group(['prefix' => 'employee-isoman'], function () {
			Route::get('/', 'EmployeeIsomanController@index')->name('employee-isoman.index')->middleware('permission:employee-isoman');
            Route::get('/create', 'EmployeeIsomanController@create')->name('employee-isoman.create')->middleware('permission:employee-isoman');
			Route::get('/cetak/{id}', 'EmployeeIsomanController@cetak')->name('employee-isoman.cetak');
			Route::get('/create_direct', 'EmployeeIsomanController@create_direct')->name('employee-isoman.create_direct')->middleware('permission:employee-isoman');
			Route::post('/store', 'EmployeeIsomanController@store')->name('employee-isoman.store')->middleware('permission:employee-isoman');
			Route::post('/', 'EmployeeIsomanController@store_direct')->name('employee-isoman.store_direct')->middleware('permission:employee-isoman');
			Route::get('/approval', 'EmployeeIsomanController@approval')->name('employee-isoman.approval');
			Route::post('/{employee_isoman}/approve', 'EmployeeIsomanController@approve')->name('employee-isoman.approve')->middleware('approval');
			Route::get('/edit/{id}', 'EmployeeIsomanController@edit')->name('employee-isoman.edit');
			Route::post('/update', 'EmployeeIsomanController@update')->name('employee-isoman.update');
        });

	//izin wfh
	 Route::group(['prefix' => 'employee-wfh'], function () {
			Route::get('/', 'EmployeeWfhController@index')->name('employee-wfh.index')->middleware('permission:create-employee-wfh');
            Route::get('/create', 'EmployeeWfhController@create')->name('employee-wfh.create')->middleware('permission:create-employee-wfh');
			Route::post('/approve_update', 'EmployeeWfhController@approve_update')->name('employee-wfh.approve_update');
			Route::get('/cetak/{id}', 'EmployeeWfhController@cetak')->name('employee-wfh.cetak');
			Route::get('/create_direct', 'EmployeeWfhController@create_direct')->name('employee-wfh.create_direct')->middleware('permission:create-employee-wfh');
			Route::post('/store', 'EmployeeWfhController@store')->name('employee-wfh.store')->middleware('permission:create-employee-wfh');
			Route::post('/', 'EmployeeWfhController@store_direct')->name('employee-wfh.store_direct')->middleware('permission:create-employee-wfh');
			Route::get('/approval', 'EmployeeWfhController@approval')->name('employee-wfh.approval');
			Route::post('/{employee_wfh}/approve', 'EmployeeWfhController@approve')->name('employee-wfh.approve')->middleware('approval');
			Route::get('/edit/{id}', 'EmployeeWfhController@edit')->name('employee-wfh.edit');
			Route::post('/update', 'EmployeeWfhController@update')->name('employee-wfh.update');
        });

	//pkwt
	 Route::group(['prefix' => 'PKWT'], function () {
        Route::post('datatable', 'PKWTController@datatable')->name('PKWT.datatable')->middleware('permission:read-pkwt');


        Route::get('/', 'PKWTController@index')->name('PKWT.index')->middleware('permission:read-pkwt');
		Route::get('list', 'PKWTController@list_pkwt')->name('PKWT.list')->middleware('permission:read-pkwt');
        Route::post('/', 'PKWTController@store')->name('PKWT.store')->middleware('permission:create-pkwt');
        Route::get('/{id}', 'PKWTController@show')->name('PKWT.show')->middleware('permission:read-pkwt');
        Route::put('/{pkwt}', 'PKWTController@update')->name('PKWT.update')->middleware('permission:update-pkwt');
        Route::delete('/{id}', 'PKWTController@destroy')->name('PKWT.destroy')->middleware('permission:delete-pkwt');

		Route::get('/detail/{id}', 'PKWTController@detail')->name('PKWT.detail');
    });


    Route::group(['prefix' => 'PASUB'], function () {
        Route::post('datatable', 'PASUBController@datatable')->name('PASUB.datatable')->middleware('permission:read-pasub');


        Route::get('/', 'PASUBController@index')->name('PASUB.index')->middleware('permission:read-pasub');
		Route::get('list', 'PASUBController@list_pkwt')->name('PASUB.list')->middleware('permission:read-pasub');
        Route::post('/', 'PASUBController@store')->name('PASUB.store')->middleware('permission:create-pasub');
        Route::get('/{id}', 'PASUBController@show')->name('PASUB.show')->middleware('permission:read-pasub');
        Route::put('/{PASUB}', 'PASUBController@update')->name('PASUB.update')->middleware('permission:update-pasub');
        Route::delete('/{id}', 'PASUBController@destroy')->name('PASUB.destroy')->middleware('permission:delete-pasub');

		Route::get('/detail/{id}', 'PASUBController@detail')->name('PASUB.detail');
    });

	 Route::group(['prefix' => 'PAPeriode'], function () {
        Route::post('datatable', 'PAPeriodeController@datatable')->name('PAPeriode.datatable')->middleware('permission:read-pasub');


        Route::get('/', 'PAPeriodeController@index')->name('PAPeriode.index')->middleware('permission:read-pasub');
		Route::get('list', 'PAPeriodeController@list_pkwt')->name('PAPeriode.list')->middleware('permission:read-pasub');
        Route::post('/', 'PAPeriodeController@store')->name('PAPeriode.store')->middleware('permission:create-pasub');
        Route::get('/{id}', 'PAPeriodeController@show')->name('PAPeriode.show')->middleware('permission:read-pasub');
        Route::put('/{PAPeriode}', 'PAPeriodeController@update')->name('PAPeriode.update')->middleware('permission:update-pasub');
		Route::get('/generate/{id}', 'PAPeriodeController@generate')->name('PAPeriode.generate');
        Route::delete('/{id}', 'PAPeriodeController@destroy')->name('PAPeriode.destroy')->middleware('permission:delete-pasub');

		Route::get('/detail/{id}', 'PAPeriodeController@detail')->name('PAPeriode.detail');
    });


	 Route::group(['prefix' => 'PAForm'], function () {

        Route::get('/', 'PAFormController@index')->name('PAForm.index')->middleware('permission:read-paform');
		Route::get('/detail/{id}', 'PAFormController@detail')->name('PAForm.detail')->middleware('permission:read-paform');
        Route::get('/edit/{id}', 'PAFormController@edit')->name('PAForm.edit');
		Route::get('/approval', 'PAFormController@approval')->name('PAForm.approval');
		Route::post('/submit_approval', 'PAFormController@submit_approval')->name('PAForm.submit_approval');
		Route::post('/submit_edit', 'PAFormController@submit_edit')->name('PAForm.submit_edit')->middleware('permission:update-paform');
        Route::get('/cetak/{id}', 'PAFormController@cetak')->name('PAForm.cetak')->middleware('permission:read-paform');
		Route::post('/remove', 'PAFormController@remove')->name('PAForm.remove')->middleware('permission:delete-paform');

		Route::get('/detail/{id}', 'PAFormController@detail')->name('PAForm.detail');
    });

	Route::group(['prefix' => 'ListPKWT'], function () {
        Route::post('datatable', 'ListPKWTController@datatable')->name('ListPKWT.datatable')->middleware('permission:read-pkwt');


        Route::get('/', 'ListPKWTController@index')->name('ListPKWT.index')->middleware('permission:read-pkwt');
        Route::post('/', 'ListPKWTController@store')->name('ListPKWT.store')->middleware('permission:create-pkwt');
        Route::get('/{id}', 'ListPKWTController@show')->name('ListPKWT.show')->middleware('permission:read-pkwt');
		Route::get('/cetak/{id}', 'ListPKWTController@cetak')->name('ListPKWT.cetak')->middleware('permission:read-pkwt');
        Route::put('/{listpkwt}', 'ListPKWTController@update')->name('ListPKWT.update')->middleware('permission:update-pkwt');
        Route::delete('/{id}', 'ListPKWTController@destroy')->name('ListPKWT.destroy')->middleware('permission:delete-pkwt');

		Route::get('/detail/{id}', 'ListPKWTController@detail')->name('ListPKWT.detail');
		Route::get('export/excel', 'ListPKWTController@excel')->name('ListPKWT.excel')->middleware('permission:export-pkwt');
        Route::get('export/csv', 'ListPKWTController@csv')->name('ListPKWT.csv')->middleware('permission:export-pkwt');
        Route::get('export/pdf', 'ListPKWTController@pdf')->name('ListPKWT.pdf')->middleware('permission:export-pkwt');

    });



	Route::group(['prefix' => 'PKDTL'], function () {
        Route::post('datatable', 'PKDTLController@datatable')->name('PKDTL.datatable')->middleware('permission:read-pkwt');


        //Route::get('/', 'PKDTLController@index')->name('PKDTL.index')->middleware('permission:read-pkwt');
        Route::post('/', 'PKDTLController@store')->name('PKDTL.store')->middleware('permission:create-pkwt');
        Route::get('/{id}', 'PKDTLController@show')->name('PKDTL.show')->middleware('permission:read-pkwt');
        Route::put('/{pkwt}', 'PKDTLController@update')->name('PKDTL.update')->middleware('permission:update-pkwt');
        Route::delete('/{id}', 'PKDTLController@destroy')->name('PKDTL.destroy')->middleware('permission:delete-pkwt');
    });

	Route::group(['prefix' => 'PADTL'], function () {
        Route::post('datatable', 'PADTLController@datatable')->name('PADTL.datatable')->middleware('permission:read-pasub');

        Route::post('/', 'PADTLController@store')->name('PADTL.store')->middleware('permission:create-pasub');
        Route::get('/{id}', 'PADTLController@show')->name('PADTL.show')->middleware('permission:read-pasub');
        Route::put('/{PASUB}', 'PADTLController@update')->name('PADTL.update')->middleware('permission:update-pasub');
        Route::delete('/{id}', 'PADTLController@destroy')->name('PADTL.destroy')->middleware('permission:delete-pasub');
    });

	//izin lembur
	 Route::group(['prefix' => 'employee-lembur'], function () {
			Route::get('/', 'EmployeeLemburController@index')->name('employee-lembur.index')->middleware('permission:create-employee-lembur');
            Route::get('/create', 'EmployeeLemburController@create')->name('employee-lembur.create')->middleware('permission:create-employee-lembur');
			Route::post('/', 'EmployeeLemburController@store')->name('employee-lembur.store')->middleware('permission:create-employee-lembur');
			Route::get('/approval', 'EmployeeLemburController@approval')->name('employee-lembur.approval');
			Route::post('/{employee_lembur}/approve', 'EmployeeLemburController@approve')->name('employee-lembur.approve')->middleware('approval');
			Route::get('/edit/{id}', 'EmployeeLemburController@edit')->name('employee-lembur.edit');
			Route::post('/update', 'EmployeeLemburController@update')->name('employee-lembur.update');
        });

    Route::group(['prefix' => 'department'], function () {
        Route::post('datatable', 'DepartmentController@datatable')->name('department.datatable')->middleware('permission:read-department');
        Route::post('restore/{id}', 'DepartmentController@restore')->name('department.restore')->middleware('permission:restore-department');
        Route::get('export/excel', 'DepartmentController@excel')->name('department.excel')->middleware('permission:export-department');
        Route::get('export/csv', 'DepartmentController@csv')->name('department.csv')->middleware('permission:export-department');
        Route::get('export/pdf', 'DepartmentController@pdf')->name('department.pdf')->middleware('permission:export-department');

        Route::get('/', 'DepartmentController@index')->name('department.index')->middleware('permission:read-department');
        Route::post('/', 'DepartmentController@store')->name('department.store')->middleware('permission:create-department');
        Route::get('heads', 'DepartmentController@heads')->name('department.heads')->middleware('permission:update-department');
        Route::post('assign/{id}', 'DepartmentController@assign')->name('department.assign')->middleware('permission:update-department');
        Route::get('/{id}', 'DepartmentController@show')->name('department.show')->middleware('permission:read-department');
        Route::put('/{department}', 'DepartmentController@update')->name('department.update')->middleware('permission:update-department');
        Route::delete('/{id}', 'DepartmentController@destroy')->name('department.destroy')->middleware('permission:delete-department');
    });
    // Route::resource('department', 'DepartmentController');

    Route::group(['prefix' => 'job-title'], function () {
        Route::post('datatable', 'JobTitleController@datatable')->name('job-title.datatable')->middleware('permission:read-job-title');
        Route::post('restore/{id}', 'JobTitleController@restore')->name('job-title.restore')->middleware('permission:restore-job-title');
        Route::get('export/excel', 'JobTitleController@excel')->name('job-title.excel')->middleware('permission:export-job-title');
        Route::get('export/csv', 'JobTitleController@csv')->name('job-title.csv')->middleware('permission:export-job-title');
        Route::get('export/pdf', 'JobTitleController@pdf')->name('job-title.pdf')->middleware('permission:export-job-title');

        Route::get('/', 'JobTitleController@index')->name('job-title.index')->middleware('permission:read-job-title');
        Route::post('/', 'JobTitleController@store')->name('job-title.store')->middleware('permission:create-job-title');
        Route::get('/{id}', 'JobTitleController@show')->name('job-title.show')->middleware('permission:read-job-title');
        Route::put('/{job_title}', 'JobTitleController@update')->name('job-title.update')->middleware('permission:update-job-title');
        Route::delete('/{id}', 'JobTitleController@destroy')->name('job-title.destroy')->middleware('permission:delete-job-title');
    });
    // Route::resource('job-title', 'JobTitleController');

    Route::group(['prefix' => 'grade-title'], function () {
        Route::post('datatable', 'GradeTitleController@datatable')->name('grade-title.datatable')->middleware('permission:read-grade-title');
        Route::post('restore/{id}', 'GradeTitleController@restore')->name('grade-title.restore')->middleware('permission:restore-grade-title');
        Route::get('export/excel', 'GradeTitleController@excel')->name('grade-title.excel')->middleware('permission:export-grade-title');
        Route::get('export/csv', 'GradeTitleController@csv')->name('grade-title.csv')->middleware('permission:export-grade-title');
        Route::get('export/pdf', 'GradeTitleController@pdf')->name('grade-title.pdf')->middleware('permission:export-grade-title');

        Route::get('/', 'GradeTitleController@index')->name('grade-title.index')->middleware('permission:read-grade-title');
        Route::post('/', 'GradeTitleController@store')->name('grade-title.store')->middleware('permission:create-grade-title');
        Route::get('/{id}', 'GradeTitleController@show')->name('grade-title.show')->middleware('permission:read-grade-title');
        Route::put('/{grade_title}', 'GradeTitleController@update')->name('grade-title.update')->middleware('permission:update-grade-title');
        Route::delete('/{id}', 'GradeTitleController@destroy')->name('grade-title.destroy')->middleware('permission:delete-grade-title');
    });
    // Route::resource('grade-title', 'GradeTitleController');

    Route::group(['prefix' => 'level-title'], function () {
        Route::post('datatable', 'LevelTitleController@datatable')->name('level-title.datatable')->middleware('permission:read-level-title');
        Route::post('restore/{id}', 'LevelTitleController@restore')->name('level-title.restore')->middleware('permission:restore-level-title');
        Route::get('export/excel', 'LevelTitleController@excel')->name('level-title.excel')->middleware('permission:export-level-title');
        Route::get('export/csv', 'LevelTitleController@csv')->name('level-title.csv')->middleware('permission:export-level-title');
        Route::get('export/pdf', 'LevelTitleController@pdf')->name('level-title.pdf')->middleware('permission:export-level-title');

        Route::get('/', 'LevelTitleController@index')->name('level-title.index')->middleware('permission:read-level-title');
        Route::post('/', 'LevelTitleController@store')->name('level-title.store')->middleware('permission:create-level-title');
        Route::get('/{id}', 'LevelTitleController@show')->name('level-title.show')->middleware('permission:read-level-title');
        Route::put('/{level_title}', 'LevelTitleController@update')->name('level-title.update')->middleware('permission:update-level-title');
        Route::delete('/{id}', 'LevelTitleController@destroy')->name('level-title.destroy')->middleware('permission:delete-level-title');
    });
    // Route::resource('level-title', 'LevelTitleController');

    Route::group(['prefix' => 'company-region'], function () {
        Route::post('datatable', 'CompanyRegionController@datatable')->name('company-region.datatable')->middleware('permission:read-company-region');
        Route::post('restore/{id}', 'CompanyRegionController@restore')->name('company-region.restore')->middleware('permission:restore-company-region');
        Route::get('export/excel', 'CompanyRegionController@excel')->name('company-region.excel')->middleware('permission:export-company-region');
        Route::get('export/csv', 'CompanyRegionController@csv')->name('company-region.csv')->middleware('permission:export-company-region');
        Route::get('export/pdf', 'CompanyRegionController@pdf')->name('company-region.pdf')->middleware('permission:export-company-region');

        Route::get('/', 'CompanyRegionController@index')->name('company-region.index')->middleware('permission:read-company-region');
        Route::post('/', 'CompanyRegionController@store')->name('company-region.store')->middleware('permission:create-company-region');
        Route::get('/{id}', 'CompanyRegionController@show')->name('company-region.show')->middleware('permission:read-company-region');
        Route::put('/{company_region}', 'CompanyRegionController@update')->name('company-region.update')->middleware('permission:update-company-region');
        Route::delete('/{id}', 'CompanyRegionController@destroy')->name('company-region.destroy')->middleware('permission:delete-company-region');
    });
    // Route::resource('company-region', 'CompanyRegionController');

    Route::group(['prefix' => 'acl', 'middleware' => 'permission:access-settings'], function () {

        Route::group(['prefix' => 'role'], function () {
            Route::get('permissions/{id}', 'RoleController@permissions')->name('role.permissions');
            Route::post('permissions/{id}', 'RoleController@assign_permissions')->name('role.assign');
        });
        Route::resource('role', 'RoleController');

        Route::group(['prefix' => 'permission'], function () { });
        Route::resource('permission', 'PermissionController');

        Route::group(['prefix' => 'user'], function () {
            // Route::get('roles', 'UserController@roles')->name('user.roles');

        });
        Route::resource('user', 'UserController');
    });

    Route::group(['prefix' => 'setting', 'middleware' => 'permission:access-settings'], function () {
        Route::get('/', 'SettingController@index')->name('setting.index');
        Route::post('/', 'SettingController@store')->name('setting.store');
        Route::post('/refresh', 'SettingController@refresh')->name('setting.refresh');
        Route::post('/flush', 'SettingController@flush')->name('setting.flush');
        Route::get('/download_backup/{id}', 'SettingController@download_backup')->name('setting.download_backup');
        Route::post('/reset_password', 'SettingController@reset_password')->name('setting.reset_password');
        Route::post('/setting_approval_ptk', 'SettingController@setting_approval_ptk')->name('setting.setting_approval_ptk');
    });

    Route::group(['prefix' => 'account'], function () {
        Route::get('/', 'AccountController@index')->name('account.index');
        Route::put('/', 'AccountController@update')->name('account.update');
        Route::get('/setting', 'AccountController@setting')->name('account.setting');
        Route::get('notification', 'AccountController@notification')->name('account.notification');
        Route::post('marksRead', 'AccountController@marksRead')->name('account.marksRead');
    });

	Route::group(['prefix' => 'job'], function () {
			Route::get('/', 'JobController@index')->name('job.index')->middleware('permission:submission-job');
            Route::get('/detail/{id}', 'JobController@detail')->name('job.detail');
            Route::get('/edit/{id}', 'JobController@edit')->name('job.edit');
			Route::post('/close', 'JobController@close')->name('job.close');
            Route::get('/create', 'JobController@create')->name('job.create')->middleware('permission:submission-job');
            Route::get('/get_indirect_superior', 'JobController@get_indirect_superior')->name('job.get_indirect_superior')->middleware('permission:submission-job');
            Route::post('/submit', 'JobController@submit')->name('job.submit')->middleware('permission:submission-job');
            Route::post('/submit_edit', 'JobController@submit_edit')->name('job.submit_edit')->middleware('permission:submission-job');
            Route::any('/export', 'JobController@export')->name('job.export')->middleware('permission:modify-job');
            Route::post('/remove', 'JobController@remove')->name('job.remove')->middleware('permission:modify-job');
    });

	Route::group(['prefix' => 'apply'], function () {
			Route::get('/', 'ApplyController@index')->name('apply.index')->middleware('permission:submission-apply');
            Route::get('/detail/{id}', 'ApplyController@detail')->name('apply.detail');
            Route::get('/cetak/{id}', 'ApplyController@cetak')->name('apply.create')->middleware('permission:submission-apply');
            Route::post('/export', 'ApplyController@export')->name('apply.export')->middleware('permission:modify-apply');
            //tambah id di remove / hapus
            Route::delete('/remove/{id}', 'ApplyController@remove')->name('apply.remove')->middleware('permission:modify-apply');
            //end tambah id di remove / hapus
			Route::post('/karyawan', 'ApplyController@karyawan')->name('apply.karyawan')->middleware('permission:modify-apply');
			Route::post('/{apply}/approve', 'ApplyController@approve')->name('apply.approve')->middleware('permission:modify-apply');
    });

    Route::group(['prefix' => 'report', 'middleware' => 'permission:access-reports'], function () {
        Route::get('/leave', 'ReportController@leave')->name('report.leave');
        Route::post('/leave', 'ReportController@leave')->name('report.leave');

		Route::get('/dinas', 'ReportController@dinas')->name('report.dinas');
		Route::post('/dinas', 'ReportController@dinas')->name('report.dinas');

		Route::get('/isoman', 'ReportController@isoman')->name('report.isoman');
		Route::post('/isoman', 'ReportController@isoman')->name('report.isoman');

		Route::get('/wfh', 'ReportController@wfh')->name('report.wfh');
		Route::post('/wfh', 'ReportController@wfh')->name('report.wfh');

		Route::get('/lembur', 'ReportController@lembur')->name('report.lembur');
		Route::post('/lembur', 'ReportController@lembur')->name('report.lembur');

		Route::any('/resign', 'ReportController@resign')->name('report.resign');

        Route::post('/delete_leave', 'ReportController@delete_leave')->name('report.delete_leave');

		Route::post('/delete_dinas', 'ReportController@delete_dinas')->name('report.delete_dinas');
		Route::post('/delete_isoman', 'ReportController@delete_isoman')->name('report.delete_isoman');
		Route::post('/delete_wfh', 'ReportController@delete_wfh')->name('report.delete_wfh');

		Route::post('/delete_lembur', 'ReportController@delete_lembur')->name('report.delete_lembur');

        Route::post('import', 'ReportController@leave_import')->name('report.leave.import')->middleware('permission:import-report-leave');
        Route::post('export/do_export', 'ReportController@do_export')->name('report.leave.do_export')->middleware('permission:export-report-leave');
        Route::get('export/leave/excel', 'ReportController@leave_excel')->name('report.leave.excel')->middleware('permission:export-report-leave');
        Route::get('export/leave/csv', 'ReportController@leave_csv')->name('report.leave.csv')->middleware('permission:export-report-leave');
        Route::get('export/leave/pdf', 'ReportController@leave_pdf')->name('report.leave.pdf')->middleware('permission:export-report-leave');

		Route::post('export/do_dinas_export', 'ReportController@do_dinas_export')->name('report.dinas.do_dinas_export')->middleware('permission:export-report-leave');
        Route::get('export/dinas/excel', 'ReportController@dinas_excel')->name('report.dinas.excel')->middleware('permission:export-report-leave');
        Route::get('export/dinas/csv', 'ReportController@dinas_csv')->name('report.dinas.csv')->middleware('permission:export-report-leave');
        Route::get('export/dinas/pdf', 'ReportController@dinas_pdf')->name('report.dinas.pdf')->middleware('permission:export-report-leave');

		Route::post('export/do_isoman_export', 'ReportController@do_isoman_export')->name('report.isoman.do_isoman_export')->middleware('permission:export-report-leave');
        Route::get('export/isoman/excel', 'ReportController@isoman_excel')->name('report.isoman.excel')->middleware('permission:export-report-leave');
        Route::get('export/isoman/csv', 'ReportController@isoman_csv')->name('report.isoman.csv')->middleware('permission:export-report-leave');
        Route::get('export/isoman/pdf', 'ReportController@isoman_pdf')->name('report.isoman.pdf')->middleware('permission:export-report-leave');

		Route::post('export/do_wfh_export', 'ReportController@do_wfh_export')->name('report.wfh.do_wfh_export')->middleware('permission:export-report-leave');
        Route::get('export/wfh/excel', 'ReportController@wfh_excel')->name('report.wfh.excel')->middleware('permission:export-report-leave');
        Route::get('export/wfh/csv', 'ReportController@wfh_csv')->name('report.wfh.csv')->middleware('permission:export-report-leave');
        Route::get('export/wfh/pdf', 'ReportController@wfh_pdf')->name('report.wfh.pdf')->middleware('permission:export-report-leave');

		Route::post('export/do_lembur_export', 'ReportController@do_lembur_export')->name('report.lembur.do_lembur_export')->middleware('permission:export-report-leave');
        Route::get('export/lembur/excel', 'ReportController@lembur_excel')->name('report.lembur.excel')->middleware('permission:export-report-leave');
        Route::get('export/lembur/csv', 'ReportController@lembur_csv')->name('report.lembur.csv')->middleware('permission:export-report-leave');
        Route::get('export/lembur/pdf', 'ReportController@lembur_pdf')->name('report.lembur.pdf')->middleware('permission:export-report-leave');

		Route::get('export/do_resign_export', 'ReportController@do_resign_export')->name('report.resign.do_resign_export')->middleware('permission:export-report-leave');
        Route::get('export/resign/excel', 'ReportController@resign_excel')->name('report.resign.excel')->middleware('permission:export-report-leave');
        Route::get('export/resign/csv', 'ReportController@resign_csv')->name('report.resign.csv')->middleware('permission:export-report-leave');
        Route::get('export/resign/pdf', 'ReportController@resign_pdf')->name('report.resign.pdf')->middleware('permission:export-report-leave');
    });

	Route::group(['prefix' => 'reportpa', 'middleware' => 'permission:reportpa'], function () {
        Route::get('/', 'ReportPAController@index')->name('reportpa.index');
		Route::post('/export', 'ReportPAController@export')->name('reportpa.export')->middleware('permission:export-reportpa');

    });


    Route::group(['prefix' => 'pengajuan'], function () {
        //Pengajuan Training
        Route::get('/training', 'TrainingController@submission')->name('pengajuan.training')->middleware('permission:submission-training');
        Route::post('/training', 'TrainingController@submission_store')->name('pengajuan.training.submit')->middleware('permission:submission-training');
        Route::get('/training/approval', 'TrainingController@approval')->name('pengajuan.training.approval');
        Route::post('/training/approval', 'TrainingController@approve')->name('pengajuan.training.approve');
        Route::post('/training/migrate', 'TrainingController@submission_migrate')->name('pengajuan.training.migrate');
        Route::get('/training/{id}', 'TrainingController@training_submissions_edit')->name('pengajuan.training.edit')->middleware('permission:submission-training');
        Route::post('/training/{id}', 'TrainingController@submission_update')->name('pengajuan.training.update')->middleware('permission:submission-training');
        Route::delete('/training/{id}', 'TrainingController@training_submissions_delete')->name('pengajuan.training.delete')->middleware('permission:submission-training');

        //Pengajuan PTK
        Route::group(['prefix' => 'ptk'], function () {
            Route::get('/', 'PTKController@index')->name('pengajuan.ptk')->middleware('permission:submission-ptk');
            Route::get('/approval', 'PTKController@approval')->name('pengajuan.ptk.approval');
            Route::post('/submit_approval', 'PTKController@submit_approval')->name('pengajuan.ptk.submit_approval');
            Route::get('/detail/{id}', 'PTKController@detail')->name('pengajuan.ptk.detail');
            Route::get('/edit/{id}', 'PTKController@edit')->name('pengajuan.ptk.edit');
            Route::get('/cetak/{id}', 'PTKController@cetak')->name('pengajuan.ptk.cetak')->middleware('permission:cetak-ptk');
            Route::get('/create', 'PTKController@create')->name('pengajuan.ptk.create')->middleware('permission:submission-ptk');
            Route::get('/get_indirect_superior', 'PTKController@get_indirect_superior')->name('pengajuan.ptk.get_indirect_superior')->middleware('permission:submission-ptk');
            Route::post('/submit', 'PTKController@submit')->name('pengajuan.ptk.submit')->middleware('permission:submission-ptk');
            Route::post('/submit_edit', 'PTKController@submit_edit')->name('pengajuan.ptk.submit_edit')->middleware('permission:submission-ptk');
            Route::post('/close', 'PTKController@close')->name('pengajuan.ptk.close')->middleware('permission:modify-ptk');
            Route::post('/outstanding', 'PTKController@outstanding')->name('pengajuan.ptk.outstanding')->middleware('permission:modify-ptk');
            Route::post('/export', 'PTKController@export')->name('pengajuan.ptk.export')->middleware('permission:modify-ptk');
            Route::post('/remove', 'PTKController@remove')->name('pengajuan.ptk.remove')->middleware('permission:modify-ptk');
        });

        //Pengajuan FPK
        Route::group(['prefix' => 'fpk'], function () {
            Route::get('/', 'FPKController@index')->name('pengajuan.fpk')->middleware('permission:submission-fpk');
            Route::get('/approval', 'FPKController@approval')->name('pengajuan.fpk.approval');
            Route::post('/submit_approval', 'FPKController@submit_approval')->name('pengajuan.fpk.submit_approval');
            Route::get('/detail/{id}', 'FPKController@detail')->name('pengajuan.fpk.detail');
            Route::get('/edit/{id}', 'FPKController@edit')->name('pengajuan.fpk.edit');
			Route::get('/edit_sk/{id}', 'FPKController@edit_sk')->name('pengajuan.fpk.edit_sk');
			Route::get('/generate_sk/{id}', 'FPKController@generate_sk')->name('pengajuan.fpk.generate_sk');
			Route::get('/generate_pkwt/{id}', 'FPKController@generate_pkwt')->name('pengajuan.fpk.generate_pkwt');
			Route::get('/generate_sphk/{id}', 'FPKController@generate_sphk')->name('pengajuan.fpk.generate_sphk');
			Route::get('/no_generate_sk/{id}', 'FPKController@no_generate_sk')->name('pengajuan.fpk.no_generate_sk');
            Route::get('/cetak/{id}', 'FPKController@cetak')->name('pengajuan.fpk.cetak')->middleware('permission:cetak-fpk');
            Route::get('/cetak_sk/{id}', 'FPKController@cetak_sk')->name('pengajuan.fpk.cetak_sk')->middleware('permission:cetak-sk');
			Route::get('/cetak_pkwt/{id}', 'FPKController@cetak_pkwt')->name('pengajuan.fpk.cetak_pkwt')->middleware('permission:cetak-sk');
			Route::get('/cetak_sphk/{id}', 'FPKController@cetak_sphk')->name('pengajuan.fpk.cetak_sphk')->middleware('permission:cetak-sk');
            Route::get('/create', 'FPKController@create')->name('pengajuan.fpk.create')->middleware('permission:submission-fpk');
            Route::get('/get_indirect_superior', 'FPKController@get_indirect_superior')->name('pengajuan.fpk.get_indirect_superior')->middleware('permission:submission-fpk');
			Route::get('/get_superior', 'FPKController@get_superior')->name('pengajuan.fpk.get_superior')->middleware('permission:submission-fpk');
			Route::get('/get_data_employee', 'FPKController@get_data_employee')->name('pengajuan.fpk.get_data_employee')->middleware('permission:submission-fpk');
			Route::post('/get_dept_list','FPKController@get_dept_list')->name('pengajuan.fpk.get_dept_list')->middleware('permission:submission-fpk');
			Route::post('/get_atasan_list','FPKController@get_atasan_list')->name('pengajuan.fpk.get_atasan_list')->middleware('permission:submission-fpk');
            Route::post('/submit', 'FPKController@submit')->name('pengajuan.fpk.submit')->middleware('permission:submission-fpk');
			Route::post('/lampiran', 'FPKController@lampiran')->name('pengajuan.fpk.lampiran')->middleware('permission:lampiran-fpk');
            Route::post('/submit_edit', 'FPKController@submit_edit')->name('pengajuan.fpk.submit_edit')->middleware('permission:submission-fpk');
			Route::post('/submit_edit_sk', 'FPKController@submit_edit_sk')->name('pengajuan.fpk.submit_edit_sk')->middleware('permission:submission-fpk');
            Route::post('/close', 'FPKController@close')->name('pengajuan.fpk.close')->middleware('permission:modify-fpk');
            Route::post('/outstanding', 'FPKController@outstanding')->name('pengajuan.fpk.outstanding')->middleware('permission:modify-fpk');
            Route::post('/export', 'FPKController@export')->name('pengajuan.fpk.export')->middleware('permission:modify-fpk');
            Route::post('/remove', 'FPKController@remove')->name('pengajuan.fpk.remove')->middleware('permission:modify-fpk');
        });

    });

    Route::group(['prefix' => 'holiday'], function () {
        Route::get('/', 'HolidayController@index')->name('holiday.index')->middleware('permission:upload-holiday');
        Route::post('/upload', 'HolidayController@upload')->name('holiday.upload')->middleware('permission:upload-holiday');
        Route::post('/delete', 'HolidayController@delete')->name('holiday.delete')->middleware('permission:upload-holiday');
    });

    Route::group(['prefix' => 'employee-hk'], function () {
        Route::post('/import', 'EmployeeHKController@import_hk')->name('employee-hk.import')->middleware('permission:import-employee');
    });

    Route::get('about-hrms', 'DashboardController@about_index')->name('about-hrms');
});
