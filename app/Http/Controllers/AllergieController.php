<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Allergie;
class AllergieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
/**
 * @OA\Get(
 *      path="/api/allergie",
 *      operationId="getallergieList",
 *      tags={"Allergie pour une commit "},
 *      summary="Get list of allergy",
 *      description="Returns list of allergy",
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation"
 *       ),
 *      @OA\Response(
 *          response=404,
 *          description="No allergy found"
 *      )
 * )
 */ 
    public function index()
    {
        $allergies = Allergie::all();
       
        if ($allergies->isEmpty()) {
            return response()->json(['message' => __('messages.allergy').__('messages.not_found')], 404);
        } else {
            return response()->json(['status' => 200, 'allergies' => $allergies], 200);
        }
    }

/**
 * @OA\Post(
 *      path="/api/allergie",
 *      operationId="storeAllergie",
 *      tags={"Allergie"},
 *      summary="Create a new allergy",
 *      description="Creates a new allergy with the provided data",
 *      @OA\RequestBody(
 *          required=true,
 *          description="Allergie data",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(
 *                      property="label",
 *                      type="string",
 *                      example="Allergie label"
 *                  ),
 *              ),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=201,
 *          description="Allergie created successfully",
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Internal server error",
 *      )
 * )
 */ 
    public function store(Request $request)
    {
        // Validation des données
        $validator = validator::make($request->all(), [
            'label' => 'string',
        ]);
 
        // Vérification des erreurs de validation
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->messages()], 422);
        }
 
        // Création de la consultation
        $allergie = Allergie::create($request->all());
 
        if ($allergie) {
            return response()->json(['status' => 201,
                                     'message' => __('messages.allergy').__('messages.created')], 201);
        } else {
            return response()->json(['status' => 500,
            'message' => __('messages.allergy').__('messages.erreur')], 500);
        }
    }

/**
 * @OA\Get(
 *      path="/api/allergie/{id}",
 *      operationId="getAllergieById",
 *      tags={"Allergie"},
 *      summary="Get a specific allergy by ID",
 *      description="Retrieves an allergy based on its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the allergy to retrieve",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *          )
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Successful operation",
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Allergie not found",
 *      )
 * )
 */
    public function show(string $id)
    {
        $allergie = Allergie::find($id);
 
        if ($allergie) {
            return response()->json(['status' => 200, 'allergie' => $allergie], 200);
        } else {
            return response()->json(['status' => 404,
                                    'message' => __('messages.allergy').__('messages.not_found')], 404);
        }
    }

/**
 * @OA\Put(
 *      path="/api/allergie/{id}",
 *      operationId="updateAllergie",
 *      tags={"Allergie"},
 *      summary="Update an existing allergy",
 *      description="Updates an existing allergy with the provided data",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the allergy to update",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *          )
 *      ),
 *      @OA\RequestBody(
 *          required=true,
 *          description="Allergie data",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  type="object",
 *                  @OA\Property(
 *                      property="label",
 *                      type="string",
 *                      example="Updated Allergie label"
 *                  ),
 *              ),
 *          ),
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="Allergie updated successfully",
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Allergie not found",
 *      ),
 *      @OA\Response(
 *          response=422,
 *          description="Validation error",
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Internal server error",
 *      )
 * )
 */

    public function update(Request $request, string $id)
    {
        // Validation des données
        $validator = validator::make($request->all(), [
            'label' => 'string',
        ]);
 
        // Vérification des erreurs de validation
        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->messages()], 422);
        }
 
       
        $allergie = Allergie::find($id);
 
        if (!$allergie) {
            return response()->json(['status' => 404,
                                    'message' => __('messages.allergy').__('messages.not_found')], 404);
        }
 
        $updateResult = $allergie->update($request->all());
 
        if (!$updateResult) {
            return response()->json(['status' => 500,
                                    'message' => __('messages.allergy').__('messages.erreur')], 500);
        }
 
        return response()->json(['status' => 200,
                                'message' => __('messages.allergy').__('messages.updated')], 200);
    }

/**
 * @OA\Delete(
 *      path="/api/allergie/{id}",
 *      operationId="deleteAllergie",
 *      tags={"Allergie"},
 *      summary="Delete an existing allergy",
 *      description="Deletes an existing allergy by its ID",
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          description="ID of the allergy to delete",
 *          required=true,
 *          @OA\Schema(
 *              type="string",
 *          )
 *      ),
 *      @OA\Response(
 *          response=204,
 *          description="Allergie deleted successfully",
 *      ),
 *      @OA\Response(
 *          response=404,
 *          description="Allergie not found",
 *      ),
 *      @OA\Response(
 *          response=500,
 *          description="Internal server error",
 *      )
 * )
 */
   
    public function destroy(string $id)
    {
         $allergie = Allergie::find($id);
   
         if (!$allergie) {
             return response()->json(['status' => 404,
                                     'message' => __('messages.allergy').__('messages.not_found')], 404);
         }
    
         $deleteResult = $allergie->delete();
    
         if ($deleteResult) {
             return response()->json(['status' => 201,
                                     'message' => __('messages.allergy').__('messages.deleted')], 201);
         } else {
             return response()->json(['status' => 500,
                                     'message' => __('messages.allergy').__('messages.erreur')], 500);
         }
    }
}
