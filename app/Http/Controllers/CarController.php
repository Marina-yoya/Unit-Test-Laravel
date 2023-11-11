<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Helpers\CarHelper;

class CarController extends Controller
{

    /**
     * Display a listing of the cars.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cars = Car::with('user')->orderBy('id', 'desc')->paginate(10);

        return view('cars.dashboard', [
            'cars' => $cars,
        ]);
    }

    /**
     * Show the form for creating a new car.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('cars.create');
    }

     /**
     * Store a newly created car in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        CarHelper::validateCarRequest($request);

        $car = new Car([
            'make' => $request->input('make'),
            'model' => $request->input('model'),
            'year' => $request->input('year'),
        ]);

        $car->user_id = auth()->user()->id;
        $car->save();

        return redirect()->route('admin.car.index')->with('success', 'Car created successfully');
    }

    /**
     * Show the form for editing the specified car.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $car = Car::find($id);

        return view('cars.edit', [
            'car' => $car
        ]);
    }

    /**
     * Update the specified car in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {

        $validator = CarHelper::validateCarRequest($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $car = Car::find($id);
        $car->make = $request->make;
        $car->model = $request->model;
        $car->year = $request->year;
        $car->save();

        return redirect()->route('admin.car.index')->with('success', 'Car was updated successfully');
    }

      /**
     * Remove the specified car from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        $car = Car::find($id);
        $car->delete();

        return redirect()->route('admin.car.index')->with('success', 'Car was deleted successfully');
    }
}
