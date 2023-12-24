<?php

namespace App\Http\Controllers;

use App\Models\FormElement;
use Illuminate\Http\Request;
use App\Models\Field;
use App\Models\Setting;
use App\Models\Option;
use Validator;
use DB;

class FormElementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('form/index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {}

    /**
     * Display the specified resource.
     */
    public function show(FormElement $formElement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FormElement $formElement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FormElement $formElement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormElement $formElement)
    {
        //
    }

    public function store_form_data(Request $request) 
    {
        DB::connection()->enableQueryLog();
         // Basic Validation
        $validator = Validator::make($request->all(), [
            'data.*.type' => 'required|string',
            'data.*.title' => 'required|string',
            'data.*.description' => 'nullable|string',
            'data.*.id' => 'required|string',
            'data.*.maxCharactersEnabled' => 'nullable|boolean',
            'data.*.sortOptions' => 'nullable|boolean',
            'data.*.maxCharacters' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        
        // Truncate tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Field::truncate();
        Setting::truncate();
        Option::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
       


        foreach ($request->all() as $element) {
            // Store Field Data
            $field = Field::create([
                'type' => $element['type'],
                'title' => $element['title'],
                'description' => $element['description'] ?? null,
                'list_group_id' => $element['listGroupId'] ?? null,
            ]);

            // Store Settings Data
            Setting::create([
                'field_id' => $field->id,
                'required' => $element['settings']['required'],
                'sort_options' => $element['settings']['sortOptions'] ?? false,
                'max_characters' => isset($element['maxCharacters']) ? $element['maxCharacters'] : null,
                'max_characters_enabled' => $element['maxCharactersEnabled'] ?? false
            ]);

            // Store Options Data for 'radio' type
            if ($element['type'] == 'radio' && isset($element['options'])) {
                foreach ($element['options'] as $opt) {
                    if($opt['option'] != null){
                        Option::create([
                        'field_id' => $field->id,
                        'list_group_id' => isset($element['listGroupId']) ? $element['listGroupId'] : null,
                        'option' => $opt['option'],
                        ]);

                    }
                    
                }
            }
        }


        return response()->json(['message' => 'Form data stored successfully'], 200);
        
    }
}