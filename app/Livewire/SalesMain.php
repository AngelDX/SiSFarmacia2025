<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

class SalesMain extends Component{
    public $dni;
    public $nombre;
    public $search;
    public $detalle;
    public $gravada,$igv,$totalImporte;

    public function mount(){
        $this->detalle=session()->get('detalle');
        $this->calcularTotalImporte();
    }

    public function render(){
        if (strlen($this->search)>0) {
            $productos=Product::where('name','LIKE','%'.$this->search.'%')->get();
        }else{
            $productos=[];
        }
        return view('livewire.sales-main',compact('productos'));
    }

    public function buscarDNI(){
        $response = Http::get('https://api.apis.net.pe/v1/dni?numero='.$this->dni);
        $datos=(object)json_decode($response);
        $this->nombre=$datos->nombre;
    }

    public function selecionarProducto(Product $producto){
        //$this->detalle=session()->get('detalle');
        if (!$this->detalle) {
            $this->detalle=[$producto->id=>[
                "name"=>$producto->name,
                "quantity"=>1,
                "price"=>$producto->price,
                "subtotal"=>$producto->price
            ]];
        }else{ //si el carro existe pero el producto no esta en el carro
            $this->detalle[$producto->id] = [
                "name"=>$producto->name,
                "quantity"=>1,
                "price"=>$producto->price,
                "subtotal"=>$producto->price
            ];
        }
        session()->put('detalle',$this->detalle);
        $this->reset("search");
        $this->dispatch('focus-search');
        $this->calcularTotalImporte();
    }

    public function deleteProducto($id){
        unset($this->detalle[$id]);
        session()->put('detalle',$this->detalle);
        $this->calcularTotalImporte();
    }

    public function sumarCantidad($id){
        if(isset($this->detalle[$id])){
            $this->detalle[$id]['quantity']++;
            $this->detalle[$id]['subtotal']=$this->detalle[$id]['subtotal']+$this->detalle[$id]['price'];
        }
        session()->put('detalle',$this->detalle);
        $this->calcularTotalImporte();
    }

    public function restarCantidad($id){
        if(isset($this->detalle[$id])){
            $this->detalle[$id]['quantity']--;
            $this->detalle[$id]['subtotal']=$this->detalle[$id]['subtotal']-$this->detalle[$id]['price'];
            if ($this->detalle[$id]['quantity']<1) {
                unset($this->detalle[$id]);
            }
        }
        session()->put('detalle',$this->detalle);
        $this->calcularTotalImporte();
    }

    public function calcularTotalImporte(){
        //$this->detalle=session()->get('detalle');
        $sesionitems=$this->detalle;
        $total=0;
        if($this->detalle){
            foreach ($sesionitems as $id => $item) {
                $total=$total+($item['price']*$item['quantity']);
            }
        }
        $this->gravada=round($total/1.18,2);
        $this->igv=$total-round($total/1.18,2);
        $this->totalImporte=$total;
    }


    public function imprimirVoucher(){
         $printerIp = '192.168.0.101';
        $printerPort = 9100;

        $connector = null;
        $printer = null;

        try {
            // Conectarse a la impresora de red
            $connector = new NetworkPrintConnector($printerIp, $printerPort);

            // --- DEPURACION: Prueba con diferentes perfiles ---
            // 1. Si tu impresora es de 80mm, prueba con "POS-80"
            // $profile = CapabilityProfile::load("POS-80");

            // 2. Si no estás seguro, prueba con el perfil por defecto
            $profile = CapabilityProfile::load("default"); // Perfil más genérico para probar

            // 3. También puedes probar sin perfil (solo para descartar)
            // $printer = new Printer($connector);
            // Si usas esta línea, comenta la siguiente de CapabilityProfile
            // $printer = new Printer($connector, $profile); // Descomenta si usas un perfil

            $printer = new Printer($connector, $profile);


           // --- Contenido simplificado para prueba (si no estás usando el de la venta) ---
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("--- MI EMPRESA ---\n");
            $printer->text("¡Ticket de Prueba!\n");
            $printer->text("--------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Fecha: " . date('d/m/Y H:i:s') . "\n");
            $printer->text("Linea de producto 1\n");
            $printer->text("Linea de producto 2\n");
            $printer->text("--------------------------------\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("Total: S/. 0.00\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Vuelva Pronto!\n");
            $printer->feed(2); // Alimentar 2 líneas para un mejor corte
            $printer->cut();   // Cortar el papel

            return back()->with('success', 'Voucher de prueba impreso correctamente.');

        } catch (\Exception $e) {
            // Este es el mensaje de error *real* que te interesa ahora.
            // Asegúrate de que este mensaje se muestre en tu interfaz o en los logs.
            return back()->with('error', 'Error al imprimir el voucher: ' . $e->getMessage());
        } finally {
            // Este bloque siempre se ejecutará si la ejecución llega hasta aquí.
            // El error "Print connector was not finalized" indica que NO se llegó a este punto
            // o que $printer era null cuando se intentó cerrar.
            if ($printer) { // Solo intenta cerrar si $printer fue inicializado con éxito
                $printer->close();
            } else {
                // Si llegamos aquí y $printer es null, significa que la excepción
                // ocurrió durante la creación del $printer (o $connector)
                // y el mensaje de error ya debería haberse capturado arriba.
                echo('Advertencia: El objeto $printer no se inicializó, no se pudo cerrar la conexión en finally.');
            }
        }
    }
}
