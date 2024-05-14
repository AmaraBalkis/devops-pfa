<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\ConsultationType;
use Illuminate\Support\Facades\Validator;
 
 
class ConsultationTypeController extends Controller
{
 
     /**
     * @OA\Get(
     *      path="/api/consultationType",
     *      operationId="getConsultationsTypeList",
     *      tags={"ConsultationsType"},
     *      summary="Get list of consultations type",
     *      description="Returns list of consultations type",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"
     *       ),
     *      @OA\Response(
     *          response=404,
     *          description="No consultations found"
     *      )
     * )
     */
 
 
    public function index()
    {
        $consultationTypes = ConsultationType::all();
 
        if ($consultationTypes->isEmpty()) {
            return response()->json(['status' => 404,
                                    'message' => __('messages.consultation_type').__('messages.erreur')], 404);
        }
 
        return response()->json(['status' => 200, 'consultationTypes' => $consultationTypes], 200);
    }
 
    /**
 * @OA\Post(
 *      path="/api/consultationType",
 *      operationId="storeConsultationType",
 *     tags={"ConsultationsType"},
 *      summary="Store a new consultation type",
 *      description="Stores a new consultation type",
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"label"},
 *              @OA\Property(property="label", type="string", example="Example Label")
 *          )
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Consultation type created successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=201),
 *              @OA\Property(property="message", type="string", example="Consultation type created successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=422),
 *              @OA\Property(property="errors", type="object", example={"label": {"The label field is required."}})
 *          )
 *      )
 * )
 */
 
 
    public function store(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'label' => 'required|string',
        ]);
 
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->messages()], 422);
        }
 
        $consultationType = ConsultationType::create($request->all());
 
        return response()->json(['status' => 201,
                                'message' => __('messages.consultation_type').__('messages.created')], 201);
    }
 
/**
 * Affiche un type de consultation spécifique.
 *
 * @OA\Get(
 *      path="/api/consultationType/{id}",
 *      operationId="getConsultationTypeById",
 *      tags={"ConsultationsType"},
 *      summary="Get a specific consultation Type",
 *      description="Returns details of a specific consultation Type",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the consultation Type to retrieve",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=200),
 *              @OA\Property(property="message", type="string", example="Consultation Type found successfully"),
 *              @OA\Property(
 *                  property="consultationType",
 *                  type="object",
 *                  @OA\Property(property="id", type="integer", format="int64"),
 *                  @OA\Property(property="label", type="string")
 *              )
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Consultation type not found",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=404),
 *              @OA\Property(property="message", type="string", example="Consultation Type not found")
 *          )
 *      )
 * )
 */
    public function show(string $id)
    {
        $consultationType = ConsultationType::find($id);
 
        if (!$consultationType) {
            return response()->json(['status' => 404,
                                    'message' => __('messages.consultation_type').__('messages.not_found')], 404);
        }
 
        return response()->json(['status' => 200, 'consultationType' => $consultationType], 200);
    }
 
   /**
 * @OA\Put(
 *      path="/api/consultationType/{id}",
 *      operationId="updateConsultationType",
 *      tags={"ConsultationsType"},
 *      summary="Update a specific consultation Type",
 *      description="Update details of a specific consultation Type",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the consultation Type to update",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          @OA\JsonContent(
 *              required={"label"},
 *              @OA\Property(property="label", type="string", example="Updated Label")
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Consultation Type updated successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=200),
 *              @OA\Property(property="message", type="string", example="Consultation Type updated successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Consultation Type not found",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=404),
 *              @OA\Property(property="message", type="string", example="Consultation Type not found")
 *          )
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=422),
 *              @OA\Property(property="errors", type="object", example={"label": {"The label field is required."}})
 *          )
 *      )
 * )
 */
 
    public function update(Request $request, string $id)
    {
        $validator = validator()->make($request->all(), [
            'label' => 'required|string',
        ]);
 
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->messages()], 422);
        }
 
        $consultationType = ConsultationType::find($id);
 
        if (!$consultationType) {
            return response()->json(['status' => 404,
                                    'message' => __('messages.consultation_type').__('messages.not_found')], 404);
        }
 
        $updateResult=$consultationType->update($request->all());
        if (!$updateResult) {
            return response()->json(['status' => 500,
                                    'message' => __('messages.consultation').__('messages.erreur')], 500);
        }
 
        return response()->json(['status' => 200,
                                'message' => __('messages.consultation_type').__('messages.updated')], 200);
    }
 
 /**
 * @OA\Delete(
 *      path="/api/consultationType/{id}",
 *      operationId="deleteConsultationType",
 *      tags={"ConsultationsType"},
 *      summary="Delete a specific consultation Type",
 *      description="Deletes a specific consultation Type",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the consultation Type to delete",
 *          @OA\Schema(
 *              type="string"
 *          )
 *      ),
 *      @OA\Response(
 *          response=204,
 *          description="Consultation Type deleted successfully",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=204),
 *              @OA\Property(property="message", type="string", example="Consultation Type deleted successfully")
 *          )
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Consultation Type not found",
 *          @OA\JsonContent(
 *              @OA\Property(property="status", type="integer", example=404),
 *              @OA\Property(property="message", type="string", example="Consultation Type not found")
 *          )
 *      )
 * )
 */
 
 
    public function destroy(string $id)
    {
        $consultationType = ConsultationType::find($id);
 
        if (!$consultationType) {
            return response()->json(['status' => 404,
                                    'message' => __('messages.consultation_type').__('messages.not_found')], 404);
        }
 
        $consultationType->delete();
 
        return response()->json(['status' => 204,
                                'message' => __('messages.consultation_type').__('messages.deleted')], 204);
    }
}