<?php

namespace LaraDev\Http\Controllers\Web;

use Illuminate\Support\Facades\Mail;
use LaraDev\Mail\Web\Contact;
use LaraDev\Property;
use Illuminate\Http\Request;
use LaraDev\Http\Controllers\Controller;
use Nexmo\Call\Filter;

class WebController extends Controller
{
    public function home ()
    {
        $head = $this->seo->render(
            env('APP_NAME') . ' - Imóveis com preços imbativeis',
            'Encontre os imóveis dos seus sonhos na melhor imobiliária de São Paulo',
            route('web.home'),
            asset('frontend/assets/images/share.png')
        );

        // Retornando imoveis para venda com eles disponiveis
        $propertiesForSale = Property::sale()->available()->limit(3)->get();
        $propertiesForRent = Property::rent()->available()->limit(3)->get();

        return view ('web.home', [
            'head' => $head,
            'propertiesForSale' => $propertiesForSale,
            'propertiesForRent' => $propertiesForRent
        ]);
    }

    public function spotligth ()
    {
        return view('web.spotligth');
    }

    public function rent ()
    {
        $head = $this->seo->render(
            env('APP_NAME') . ' - Imóveis para locação com preços imbativeis',
            'Alugue o imóvel dos seus sonhos com a melhor imobiliária de São Paulo',
            route('web.rent'),
            asset('frontend/assets/images/share.png')
        );

        $filter = new FilterController();
        // fazendo a limpeza dos filtros
        $filter->clearAllData();

        $properties = Property::rent()->available()->get();
        return view ('web.filter', [
            'head' => $head,
            'properties' => $properties,
            'type' => 'rent'
        ]);
    }

    public function rentProperty (Request $request)
    {
        // Pegando o property pela url amigavel
        $property = Property::where('slug', $request->slug)->first();

        $head = $this->seo->render(
            env('APP_NAME') . ' - Imóveis para comprar com preços imbativeis',
            $property->headline ?? $property->title,
            route('web.rentProperty', ['property' => $property->slug]),
            $property->cover());

        return view ('web.property',[
            'head' => $head,
            'property' => $property,
            'type' => 'rent'
        ]);
    }

    public function buy ()
    {
        $head = $this->seo->render(
            env('APP_NAME') . ' - Imóveis para comprar com preços imbativeis',
            'Compre o imóvel dos seus sonhos com a melhor imobiliária de São Paulo',
            route('web.rent'),
            asset('frontend/assets/images/share.png')
        );

        $filter = new FilterController();
        // fazendo a limpeza dos filtros
        $filter->clearAllData();

        $properties = Property::sale()->available()->get();
        return view ('web.filter', [
            'head' => $head,
            'properties' => $properties,
            'type' => 'sale'
        ]);
    }

    public function buyProperty (Request $request)
    {
        // Pegando o property pela url amigavel
        $property = Property::where('slug', $request->slug)->first();

        $head = $this->seo->render(
            env('APP_NAME') . ' - Imóveis para comprar com preços imbativeis',
            $property->headline ?? $property->title,
            route('web.buyProperty', ['property' => $property->slug]),
            $property->cover());

        return view ('web.property', [
            'head' => $head,
            'property' => $property,
            'type' => 'sale'
        ]);
    }

    public function filter ()
    {
        $head = $this->seo->render(
            env('APP_NAME') . ' - Encontre o melhor imovel para a sua familia',
            'Filtre o imóvel dos seus sonhos com a melhor imobiliária de São Paulo',
            route('web.filter'),
            asset('frontend/assets/images/share.png')
        );

        // pegando os registros dos imoveis pelo id
        $filter = new FilterController();
        $itemProperties = $filter->createQuery('id');

        foreach ($itemProperties as $property) {
            $properties[] = $property->id;
        }

        if (!empty($properties)) {
            $properties = Property::whereIn('id', $properties)->get();
        } else {
            $properties = Property::all();
        }

        return view ('web.filter', [
            'head' => $head,
            'properties' => $properties
        ]);
    }

    public function experience ()
    {
        $head = $this->seo->render(
            env('APP_NAME') . ' - Veja as melhores experiencias para você e sua familia',
            'Viva a melhor experiência sobre imóvel dos seus sonhos com a melhor imobiliária de São Paulo',
            route('web.experience'),
            asset('frontend/assets/images/share.png')
        );

        $filter = new FilterController();
        // fazendo a limpeza dos filtros
        $filter->clearAllData();

        $properties = Property::whereNotNull('experience')->get();

        return view('web.filter', [
            'head' => $head,
            'properties' => $properties
        ]);
    }

    public function experienceCategory (Request $request)
    {
        $head = $this->seo->render(
            env('APP_NAME') . ' - Veja as melhores experiencias para você e sua familia',
            'Viva a melhor experiência sobre imóvel dos seus sonhos com a melhor imobiliária de São Paulo',
            route('web.experience'),
            asset('frontend/assets/images/share.png')
        );

        $filter = new FilterController();
        // fazendo a limpeza dos filtros
        $filter->clearAllData();

        if ($request->slug == 'cobertura') {
            $properties = Property::where('experience', 'Cobertura')->get();

            $head = $this->seo->render(
                env('APP_NAME') . ' - Sinta a melhor experiencia em morar em um dos melhores condominios de SP.',
                'Viva a melhor experiência sobre imóvel dos seus sonhos com a melhor imobiliária de São Paulo',
                route('web.experience', ['category' => 'cobertura']),
                asset('frontend/assets/images/share.png'));

        }  elseif ($request->slug == 'alto-padrao') {
            $properties = Property::where('experience', 'Alto Padrão')->get();
        } elseif ($request->slug == 'de-frente-para-o-mar') {
            $properties = Property::where('experience', 'De Frente para o Mar')->get();
        } elseif ($request->slug == 'condominio-fechado') {
            $properties = Property::where('experience', 'Condominio Fechado')->get();
        } elseif ($request->slug == 'compacto') {
            $properties = Property::where('experience', 'Compacto')->get();
        } elseif ($request->slug == 'lojas-e-salas') {
            $properties = Property::where('experience', 'Lojas e Salas')->get();
        } else {
            $properties = Property::whereNotNull('experience')->get();
        }

        if (empty($head)) {
            $head = $this->seo->render(
                env('APP_NAME') . ' - Veja as melhores experiencias para você e sua familia',
                'Viva a melhor experiência sobre imóvel dos seus sonhos com a melhor imobiliária de São Paulo',
                route('web.experience'),
                asset('frontend/assets/images/share.png')
            );
        }

        return view('web.filter', [
            'head' => $head,
            'properties' => $properties
        ]);
    }

    public function contact ()
    {
        $head = $this->seo->render(
            env('APP_NAME') . ' - Entre em contato com a nossa administração',
            'Fale conosco e tenha um atendimento rápido e personalizado',
            route('web.contact'),
            asset('frontend/assets/images/share.png'))
        ;
        return view ('web.contact', [
            'head' => $head
        ]);
    }

    public function sendEmail(Request $request)
    {
        $data = [
            'reply_name' => $request->name,
            'reply_email' => $request->email,
            'cell' => $request->cell,
            'message' => $request->message
        ];

        Mail::send(new Contact($data));

        return redirect()->route('web.sendEmailSuccess');
    }

    public function sendEmailSuccess()
    {
        return view('web.contact_success');
    }
}
