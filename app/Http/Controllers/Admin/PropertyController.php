<?php

namespace LaraDev\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use LaraDev\Http\Controllers\Controller;
use LaraDev\Property;
use \LaraDev\Http\Requests\Admin\Property as PropertyRequest;
use LaraDev\PropertyImage;
use LaraDev\Support\Cropper;
use LaraDev\User;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $properties = Property::orderBy('id', 'DESC')->get();
        return view('admin.properties.index',[
            'properties' => $properties
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::orderBy('name')->get();
        return view('admin.properties.create',[
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PropertyRequest $request)
    {
        $createProperty = Property::create($request->all());

        // criando a url amigavel
        $createProperty->setSlug();

        // Requisitando apenas imagem
        $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        // Tive um erro
        if($validator->fails() === true) {
            return redirect()->back()->withInput()->with(['color' => 'orange', 'message' => 'Todas as imagens devem ser do tipo jpg, jpeg ou png.']);
        }

        // Verificando se foi enviado a imagem
        if($request->allFiles()) {
            foreach($request->allFiles()['files'] as $image) {
                $propertyImage = new PropertyImage();
                $propertyImage->property = $createProperty->id;
                $propertyImage->path = $image->store('properties/' . $createProperty->id);
                $propertyImage->save();
                unset($propertyImage);
            }
        }

        return redirect()->route('admin.properties.edit', [
            'property' => $createProperty->id
        ])->with(['color' => 'green', 'message' => 'Imóvel cadastrado com sucesso!']);

        /**
         * Os códigos abaixo servem para que eu possa ver o vardump
         * $property = new Property();
        $property->fill($request->all());

        var_dump($property->getAttributes());
         */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $property = Property::where('id', $id)->first();
        $users = User::orderBy('name')->get();

        return view('admin.properties.edit', [
            'property' => $property,
            'users' => $users
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PropertyRequest $request, $id)
    {
        $property = Property::where('id', $id)->first();
        $property->fill($request->all());

        $property->setSaleAttribute($request->sale);
        $property->setRentAttribute($request->rent);
        $property->setAirConditioningAttribute($request->air_conditioning);
        $property->setBarAttribute($request->bar);
        $property->setLibraryAttribute($request->library);
        $property->setBarbecueGrillAttribute($request->barbecue_grill);
        $property->setAmericanKitchenAttribute($request->american_kitchen);
        $property->setFittedKitchenAttribute($request->fitted_kitchen);
        $property->setPantryAttribute($request->pantry);
        $property->setEdiculeAttribute($request->edicule);
        $property->setOfficeAttribute($request->office);
        $property->setBathtubAttribute($request->bathtub);
        $property->setFirePlaceAttribute($request->fireplace);
        $property->setLavatoryAttribute($request->lavatory);
        $property->setFurnishedAttribute($request->furnished);
        $property->setPoolAttribute($request->pool);
        $property->setSteamRoomAttribute($request->steam_room);
        $property->setViewOfTheSeaAttribute($request->view_of_the_sea);

        $property->save();
        
        // atualizando a url amigavel
        $property->setSlug();

        // Requisitando apenas imagem
       // $validator = Validator::make($request->only('files'), ['files.*' => 'image']);

        // Tive um erro
        /*if ($validator->fails() === true){
            return redirect()->back()->withInput()->with(['color' => 'orange', 'message' => 'Todas as Imagens devem ser em JPG / PNG / SVG.']);
        } */

        // Verificando se foi enviado a imagem
        if($request->allFiles()) {
            foreach($request->allFiles()['files'] as $image) {
                $propertyImage = new PropertyImage();
                $propertyImage->property = $property->id;
                $propertyImage->path = $image->store('properties/' . $property->id);
                $propertyImage->save();
                unset($propertyImage);
            }
        }

        return redirect()->route('admin.properties.edit', [
            'property' => $property->id
        ])->with(['color' => 'green', 'message' => 'Imóvel atualizado com sucesso!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function imageSetCover(Request $request)
    {
        // Retornando apenas a imagem que cliquei como capa
        $imageSetCover = PropertyImage::where('id', $request->image)->first();
        // Pesquisando a imagem pelo imovel que recebi acima
        $allImage = PropertyImage::where('property', $imageSetCover->property)->get();

        // Limpando todas as imagens e a qual eu escolher como checado será salvo como capa
        foreach($allImage as $image) {
            $image->cover = null;
            $image->save();
        }

        $imageSetCover->cover = true;
        $imageSetCover->save();

        $json = [
            'success' => true
        ];

        return response()->json($json);
    }

    public function imageRemove(Request $request)
    {
        // To pesquisando a imagem que eu desejo deletar pelo id puxando a imagem quando clico no botão X
        $imageDelete = PropertyImage::where('id', $request->image)->first();

        Storage::delete($imageDelete->path);
        Cropper::flush($imageDelete->path);

        // removendo a imagem do banco de dados
        $imageDelete->delete();

        $json = [
            'success' => true
        ];

        return response()->json($json);
    }
}
