<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;
use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    // Obtener todos los asistentes
    public function index()
    {
        return response()->json(Attendee::all());
    }

    // Crear un nuevo asistente
    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'email' => 'required|email|unique:attendees',
            'celular' => 'required|string',
            'pais_ciudad' => 'required|string',
            'boleta' => 'required|in:virtual,presencial',
        ]);

        $attendee = Attendee::create($request->all());

        return response()->json($attendee, 201);
    }

    // Obtener un asistente por ID
    public function show($id)
    {
        $attendee = Attendee::find($id);

        if (!$attendee) {
            return response()->json(['message' => 'Asistente no encontrado'], 404);
        }

        return response()->json($attendee);
    }

    // Actualizar un asistente por ID
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nombre' => 'sometimes|required|string',
            'apellido' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:attendees,email,' . $id,
            'celular' => 'sometimes|required|string',
            'pais_ciudad' => 'sometimes|required|string',
            'boleta' => 'sometimes|required|in:virtual,presencial',
        ]);

        $attendee = Attendee::find($id);

        if (!$attendee) {
            return response()->json(['message' => 'Asistente no encontrado'], 404);
        }

        $attendee->fill($request->all());
        $attendee->save();

        return response()->json($attendee);
    }

    public function encode($text)
    {
        return response()->json([
            "original" => $text,
            "base64" => base64_encode($text)
        ]);
    }

    // Decodificar Base64
    public function decode($text)
    {
        return response()->json([
            "base64" => $text,
            "decoded" => base64_decode($text)
        ]);
    }


    // Eliminar un asistente por ID
    public function destroy(Request $request, $id)
    {
        $expectedAuth = env('SECRETKEY'); // Clave en texto plano

        // Obtener el header de autorización
        $authHeader = $request->header('Authorization');

        // Verificar si el header está presente
        if (!$authHeader || !str_starts_with($authHeader, "Basic ")) {
            return response()->json([
                "error" => "No autorizado",
                "message" => "Falta el header de autorización"
            ], 401);
        }

        // Extraer la parte codificada en Base64
        $encodedKey = substr($authHeader, 6); // Elimina "Basic " del inicio

        // Decodificar la clave Base64
        $decodedKey = base64_decode($encodedKey);

        // Comparar con la clave esperada
        if ($decodedKey !== $expectedAuth) {
            return response()->json([
                "error" => "No autorizado",
                "message" => "Clave incorrecta",
                "expected" => base64_encode($expectedAuth), // Para debug
                "received" => $encodedKey
            ], 401);
        }

        // Buscar el asistente
        $attendee = Attendee::find($id);

        if (!$attendee) {
            return response()->json(['message' => 'Asistente no encontrado'], 404);
        }

        $attendee->delete();

        return response()->json(['message' => 'Asistente eliminado correctamente']);
    }
}
