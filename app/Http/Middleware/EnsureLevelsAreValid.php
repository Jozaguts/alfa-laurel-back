<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EnsureLevelsAreValid
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $data = $request->all();
        $levels = array_column($data['questions'],'level');
        $countedLevels = array_count_values($levels);
        if ($countedLevels['B'] < $data['low']) {
           return $this->sendJsonError($countedLevels['B'],$data['low'],'BAJO');
        }else if ( $countedLevels['M'] < $data['medium']){
            return $this->sendJsonError($countedLevels['M'],$data['medium'],'MEDIO');
        }else if ($countedLevels['A'] < $data['high']) {
            return $this->sendJsonError($countedLevels['A'],$data['high'],'ALTO');
        }

        return $next($request);
    }
    public function sendJsonError(int $countLevel, $requiredLevel,string $levelName): JsonResponse
    {
        return response()->json("El numero de preguntas en nivel {$levelName} ({$requiredLevel}) que quieres asignar es mayor a la cantidad disponible ({$countLevel})",400);
    }
}
