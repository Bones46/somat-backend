<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(
    ['prefix'=>'auth'],
    function () {
        Route::post('login', 'AuthController@login');
        Route::group(['middleware'=>'auth:api'], function () {
            Route::get('logout', 'AuthController@logout');
        });
    }
);

Route::group(
    ['prefix'=>'admin'],
    function () {
        Route::group(['middleware'=>['auth:api']], function () {
            Route::get('dashboard', 'DashboardController@index');
            Route::post('dashboard', 'DashboardController@index');
            Route::get('users', 'UserController@index');
            Route::get('users/{id}/show', 'UserController@show');
            Route::post('users', 'UserController@store');
            Route::put('users/{id}', 'UserController@update');
            Route::delete('users/{id}', 'UserController@destroy');
            
            /****************************************Group Permission *******************************************************************/
            
            //role module
            Route::get('/role', 'RoleController@index')->name('role');
            Route::get('/role/{id}', 'RoleController@findById')->name('role.find');
            Route::post('/role/save', 'RoleController@save')->name('role.save');
            Route::delete('/role/{id}/delete', 'RoleController@delete')->name('role.delete');
            
            // permisison module
            Route::get('/permission', 'PermissionsController@index')->name('permission');
            Route::get('/permission/template/{id}', 'PermissionsController@getByTemplate')->name('permission.template');
            Route::post('/permission/save', 'PermissionsController@save')->name('permission.save');
            Route::delete('/permission/{id}/delete', 'PermissionsController@delete')->name('permission.delete');

            // template module
            Route::get('/template', 'TemplatesController@index')->name('template');
            Route::get('/template/{id}', 'TemplatesController@findById')->name('template.find');
            Route::post('/template/save', 'TemplatesController@save')->name('template.save');
            Route::delete('/template/{id}/delete', 'TemplatesController@delete')->name('template.delete');
            
            /******************************************* Registration ******************************************************************/
            //student registration
            Route::get('/student', 'StudentController@index')->name('student');
            Route::post('/student/save', 'StudentController@save')->name('student.save');
            Route::get('/student/{id}', 'StudentController@find')->name('student.find');
            
            //teacher registration
            Route::get('/employee', 'EmployeeController@index')->name('employee');
            Route::post('/employee/save', 'EmployeeController@save')->name('employee.save');
            Route::get('/employee/{id}/find', 'EmployeeController@find')->name('employee.find');
            Route::get('/employee/teacher', 'EmployeeController@teacher')->name('employee.teacher');

            /******************************************* setup  module *****************************************************************/
            
            //school module
            Route::get('/school', 'SchoolController@index')->name('school');
            Route::post('/school/save', 'SchoolController@save')->name('school.save');
            Route::delete('/school/{id}/delete', 'SchoolController@delete')->name('school.delete');
            Route::get('/school/{id}', 'SchoolController@find')->name('school.find');
            
            // lesson module
            Route::get('/lesson', 'LessonController@index')->name('lesson');
            Route::get('/lesson/{id}/find', 'LessonController@find')->name('lesson.find');
            Route::post('/lesson/save', 'LessonController@save')->name('lesson.save');
            Route::get('/lesson/active', 'LessonController@active')->name('lesson.active');
            Route::delete('/lesson/{id}/delete', 'lessonController@delete')->name('lessontime.delete');
            
            // lesson time module
            Route::get('/lessontime', 'LessontimeController@index')->name('lessontime');
            Route::get('/lessontime/{id}/find', 'LessontimeController@find')->name('lessontime.find');
            Route::post('/lessontime/save', 'LessontimeController@save')->name('lessontime.save');
            Route::get('/lessontime/active', 'LessontimeController@active')->name('lessontime.active');
            Route::delete('/lessontime/{id}/delete', 'LessontimeController@delete')->name('lessontime.delete');
            
             // activity  module
            Route::get('/activity', 'activityController@index')->name('activity');
            Route::get('/activity/{id}/find', 'activityController@find')->name('activity.find');
            Route::post('/activity/save', 'activityController@save')->name('activity.save');
            Route::get('/activity/active', 'activityController@active')->name('activity.active');
            Route::delete('/activity/{id}/delete', 'activityController@delete')->name('activity.delete');
            
              //setup lesson category module
            Route::get('/lessoncategory', 'LessonCategoryController@index')->name('lessoncategory');
            Route::post('/lessoncategory/save', 'LessonCategoryController@save')->name('lessoncategory.save');
            Route::get('/lessoncategory/active', 'LessonCategoryController@Active')->name('lessoncategory.active');
            
             // school level module
            Route::get('/schoollevel', 'SchoollevelController@index')->name('schoollevel');
            Route::post('/schoollevel/save', 'SchoollevelController@save')->name('schoollevel.save');
            Route::delete('/schoollevel/{id}/delete', 'SchoollevelController@delete')->name('schoollevel.delete');
            Route::get('/schoollevel/active', 'SchoollevelController@Active')->name('schoollevel.active');
          
            //region location
            Route::get('/village/{id}','RegionController@show')->name('village');
            Route::get('/subdistrict/{id}','RegionController@show')->name('subdistrict');
            Route::get('/district/{id}','RegionController@show')->name('district');
            Route::get('/province/{id}','RegionController@show')->name('province');
            Route::get('/country/{id}','RegionController@show')->name('country');
            
            // academic school
            Route::get('/academicyear', 'AcademicYearController@index')->name('schoolyear');
            Route::get('/academicyear/{id}/find', 'AcademicYearController@findById')->name('schoolyear.find');
            Route::post('/academicyear/save', 'AcademicYearController@save')->name('schoolyear.save');
            Route::get('/academicyear/active', 'AcademicYearController@Active')->name('schoolyear.active');
            
            // academic class major
            Route::get('/classmajor', 'ClassmajorController@index')->name('classmajor');
            Route::get('/classmajor/{id}/find', 'ClassmajorController@find')->name('classmajor.find');
            Route::post('/classmajor/save', 'ClassmajorController@save')->name('classmajor.save');
            Route::get('/classmajor/active', 'ClassmajorController@Active')->name('classmajor.active');
            
            // class module
            Route::get('/class', 'ClassController@index')->name('class');
            Route::get('/class/{id}/find', 'ClassController@find')->name('class.find');
            Route::post('/class/save', 'ClassController@save')->name('class.save');
            Route::get('/class/active', 'ClassController@active')->name('class.active');
            Route::delete('/class/{id}/delete', 'ClassController@delete')->name('class.delete');

            // lookup
            Route::get('/lookup', 'lookupController@index')->name('lookup');
            Route::get('/lookup/{id}/find', 'lookupController@find')->name('lookup.find');
            Route::get('/lookup/lookupcode', 'lookupController@lookupcode')->name('lookup.lookupcode');
            Route::post('/lookup/save', 'lookupController@save')->name('lookup.save');
            
            // organization
            Route::get('/organization', 'organizationController@index')->name('organization');
            Route::post('/organization/save', 'organizationController@save')->name('organization.save');
            Route::delete('/organization/{id}/delete', 'organizationController@delete')->name('organization.delete');
            
             // organization
//            Route::get('/organization', 'organizationController@index')->name('organization');
//            Route::post('/organization/save', 'organizationController@save')->name('organization.save');
//            Route::delete('/organization/{id}/delete', 'organizationController@delete')->name('organization.delete');
            
            // position
            Route::get('/position', 'positionController@index')->name('position');
            Route::get('/position/{id}/organization', 'positionController@organization')->name('position.organization');
            Route::post('/position/save', 'positionController@save')->name('position.save');
            Route::delete('/position/{id}/delete', 'positionController@delete')->name('position.delete');
            
            // job
            Route::get('/job', 'jobController@index')->name('job');
            Route::get('/job/{id}/position', 'jobController@position')->name('job.position');
            Route::post('/job/save', 'jobController@save')->name('job.save');
            Route::delete('/job/{id}/delete', 'jobController@delete')->name('job.delete');

            Route::get('/teacher', 'ClassificationController@index')->name('teacher');
            Route::get('/studentclass', 'StudentclassController@index')->name('studentclass');
            Route::get('/studentclass/{id}', 'StudentclassController@findById')->name('studentclass.find');
            Route::post('/studentclass/save', 'StudentclassController@save')->name('studentclass.save');

            Route::get('/studentclassmap', 'StudentclassmapController@index')->name('studentclassmap');
            Route::get('/studentclassmap/{id}', 'StudentclassmapController@findById')->name('studentclassmap.find');
            Route::post('/studentclassmap/save', 'StudentclassmapController@save')->name('studentclassmap.save');
            Route::get('/country/{id}','RegionController@show')->name('country');   
        });
    }
);

