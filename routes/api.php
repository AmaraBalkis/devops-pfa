<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\http\Controllers\DocumentController;
use App\http\Controllers\DocumentTypeController;
use App\http\Controllers\PrescriptionLineController;
use App\http\Controllers\SharedDocumentController;
use App\Http\Controllers\WaitingRoomController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PatientRecordController;
use App\Http\Controllers\ConsultationTypeController;
use App\Http\Controllers\AllergieController;
use App\Http\Controllers\PatientRecordAllergieController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::middleware('api_localization')->group(function () {
    Route::resource('documents', DocumentController::class);
    Route::resource('document_types', DocumentTypeController::class);
    Route::resource('prescription_line', PrescriptionLineController::class);
    Route::resource('sharedDocument', SharedDocumentController::class);
    Route::resource('waitingRoom', WaitingRoomController::class);
    Route::resource('consultations', ConsultationController::class);
    Route::resource('patientRecords', PatientRecordController::class);
    Route::resource('consultationType', ConsultationTypeController::class);
    Route::resource('allergie', AllergieController::class);
    Route::resource('patientRecordAllergie', PatientRecordAllergieController::class);
    Route::get('/prescriptions_by_patient/{patientId}', [DocumentController::class, 'getPrescriptionsByPatientId']);
    Route::get('/prescriptions_by_patientrecord/{patientRecordId}', [DocumentController::class, 'getPrescriptionsByPatientRecordId']);
    Route::get('/patientRecords/{id}/consultations', [PatientRecordController::class, 'getConsultations']); //afficher toutes les consultation dun patientrecord X

});

//Autres methodes 
// Routes pour les types de consultation
/*Route::get('consultation_types',[ConsultationTypeController::class,'index']); // GET: Récupère tous les types de consultation
Route::post('consultation_types',[ConsultationTypeController::class,'store']); // POST: Crée un nouveau type de consultation
Route::get('consultation_types/{id}',[ConsultationTypeController::class,'show']); // GET: Récupère un type de consultation spécifique
Route::put('consultation_types/{id}/edit',[ConsultationTypeController::class,'update']); // PUT: Met à jour un type de consultation existant
Route::delete('consultation_types/{id}/delete',[ConsultationTypeController::class,'destroy']); // DELETE: Supprime un type de consultation
*/