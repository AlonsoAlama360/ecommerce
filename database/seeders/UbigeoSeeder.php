<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbigeoSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('districts')->truncate();
        DB::table('provinces')->truncate();
        DB::table('departments')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $pId = 0;
        $dId = 0;
        $allProvinces = [];
        $allDistricts = [];

        // [department_id => [name, [province => [districts...]]]]
        $data = $this->getData();

        $departments = [];
        foreach ($data as $deptId => [$deptName, $provinces]) {
            $departments[] = ['id' => $deptId, 'name' => $deptName];
            foreach ($provinces as $provName => $districts) {
                $pId++;
                $allProvinces[] = ['id' => $pId, 'department_id' => $deptId, 'name' => $provName];
                foreach ($districts as $distName) {
                    $dId++;
                    $allDistricts[] = ['id' => $dId, 'province_id' => $pId, 'name' => $distName];
                }
            }
        }

        DB::table('departments')->insert($departments);
        foreach (array_chunk($allProvinces, 50) as $chunk) {
            DB::table('provinces')->insert($chunk);
        }
        foreach (array_chunk($allDistricts, 100) as $chunk) {
            DB::table('districts')->insert($chunk);
        }

        $this->command->info("Ubigeo: " . count($departments) . " departamentos, {$pId} provincias, {$dId} distritos.");
    }

    private function getData(): array
    {
        return [
            1 => ['Amazonas', [
                'Chachapoyas' => ['Chachapoyas','Asunción','Balsas','Cheto','Chiliquín','Chuquibamba','Granada','Huancas','La Jalca','Leimebamba','Levanto','Magdalena','Mariscal Castilla','Molinopampa','Montevideo','Olleros','Quinjalca','San Francisco de Daguas','San Isidro de Maino','Soloco','Sonche'],
                'Bagua' => ['Bagua','Aramango','Copallín','El Parco','Imaza','La Peca'],
                'Bongará' => ['Jumbilla','Chisquilla','Churuja','Corosha','Cuispes','Florida','Jamalca','Recta','San Carlos','Shipasbamba','Valera','Yambrasbamba'],
                'Condorcanqui' => ['Nieva','El Cenepa','Río Santiago'],
                'Luya' => ['Lamud','Camporredondo','Cocabamba','Colcamar','Conila','Inguilpata','Longuita','Lonya Chico','Luya','Luya Viejo','María','Ocalli','Ocumal','Pisuquia','Providencia','San Cristóbal','San Francisco del Yeso','San Jerónimo','San Juan de Lopecancha','Santa Catalina','Santo Tomás','Tingo','Trita'],
                'Rodríguez de Mendoza' => ['San Nicolás','Chirimoto','Cochamal','Huambo','Limabamba','Longar','Mariscal Benavides','Milpuc','Omia','Santa Rosa','Totora','Vista Alegre'],
                'Utcubamba' => ['Bagua Grande','Cajaruro','Cumba','El Milagro','Jamalca','Lonya Grande','Yamón'],
            ]],
            2 => ['Áncash', [
                'Huaraz' => ['Huaraz','Cochabamba','Colcabamba','Huanchay','Independencia','Jangas','La Libertad','Olleros','Pampas Grande','Pariacoto','Pira','Tarica'],
                'Aija' => ['Aija','Coris','Huacllán','La Merced','Succha'],
                'Antonio Raymondi' => ['Llamellín','Aczo','Chaccho','Chingas','Mirgas','San Juan de Rontoy'],
                'Asunción' => ['Chacas','Acochaca'],
                'Bolognesi' => ['Chiquián','Abelardo Pardo Lezameta','Antonio Raymondi','Aquia','Cajacay','Canis','Colquioc','Huallanca','Huasta','Huayllacayán','La Primavera','Mangas','Pacllón','San Miguel de Corpanqui','Ticllos'],
                'Carhuaz' => ['Carhuaz','Acopampa','Amashca','Anta','Ataquero','Marcará','Pariahuanca','San Miguel de Aco','Shilla','Tinco','Yungar'],
                'Carlos Fermín Fitzcarrald' => ['San Luis','San Nicolás','Yauya'],
                'Casma' => ['Casma','Buena Vista Alta','Comandante Noel','Yaután'],
                'Corongo' => ['Corongo','Aco','Bambas','Cusca','La Pampa','Yánac','Yupán'],
                'Huari' => ['Huari','Anra','Cajay','Chavín de Huántar','Huacachi','Huacchis','Huachis','Huantar','Masin','Paucas','Pontó','Rahuapampa','Rapayán','San Marcos','San Pedro de Chaná','Uco'],
                'Huarmey' => ['Huarmey','Cochapetí','Culebras','Huayán','Malvas'],
                'Huaylas' => ['Caraz','Huallanca','Huata','Huaylas','Mato','Pamparomás','Pueblo Libre','Santa Cruz','Santo Toribio','Yuracmarca'],
                'Mariscal Luzuriaga' => ['Piscobamba','Casca','Eleazar Guzmán Barrón','Fidel Olivas Escudero','Llama','Llumpa','Lucma','Musga'],
                'Ocros' => ['Ocros','Acas','Cajamarquilla','Carhuapampa','Cochas','Congas','Llipa','San Cristóbal de Raján','San Pedro','Santiago de Chilcas'],
                'Pallasca' => ['Cabana','Bolognesi','Conchucos','Huacaschuque','Huandoval','Lacabamba','Llapo','Pallasca','Pampas','Santa Rosa','Tauca'],
                'Pomabamba' => ['Pomabamba','Huayllán','Parobamba','Quinuabamba'],
                'Recuay' => ['Recuay','Cátac','Cotaparaco','Huayllapampa','Llaclín','Marca','Pampas Chico','Pararín','Tapacocha','Ticapampa'],
                'Santa' => ['Chimbote','Cáceres del Perú','Coishco','Macate','Moro','Nepeña','Nuevo Chimbote','Samanco','Santa'],
                'Sihuas' => ['Sihuas','Acobamba','Alfonso Ugarte','Cashapampa','Chingalpo','Huayllabamba','Quiches','Ragash','San Juan','Sicsibamba'],
                'Yungay' => ['Yungay','Cascapara','Mancos','Matacoto','Quillo','Ranrahirca','Shupluy','Yanama'],
            ]],
            3 => ['Apurímac', [
                'Abancay' => ['Abancay','Chacoche','Circa','Curahuasi','Huanipaca','Lambrama','Pichirhua','San Pedro de Cachora','Tamburco'],
                'Andahuaylas' => ['Andahuaylas','Andarapa','Chiara','Huancarama','Huancaray','Huayana','Kishuará','Pacobamba','Pacucha','Pampachiri','Pomacocha','San Antonio de Cachi','San Jerónimo','San Miguel de Chaccrampa','Santa María de Chicmo','Talavera','Tumay Huaraca','Turpo','Kaquiabamba','José María Arguedas'],
                'Antabamba' => ['Antabamba','El Oro','Huaquirca','Juan Espinoza Medrano','Oropesa','Pachaconas','Sabaino'],
                'Aymaraes' => ['Chalhuanca','Capaya','Caraybamba','Chapimarca','Colcabamba','Cotaruse','Huayllo','Justo Apu Sahuaraura','Lucre','Pocohuanca','San Juan de Chacña','Sañayca','Soraya','Tapairihua','Tintay','Toraya','Yanaca'],
                'Cotabambas' => ['Tambobamba','Cotabambas','Coyllurqui','Haquira','Mara','Challhuahuacho'],
                'Chincheros' => ['Chincheros','Anco Huallo','Cocharcas','Huaccana','Ocobamba','Ongoy','Uranmarca','Ranracancha','Rocchac','El Porvenir','Los Chankas'],
                'Grau' => ['Chuquibambilla','Curpahuasi','Gamarra','Huayllati','Mamara','Micaela Bastidas','Pataypampa','Progreso','San Antonio','Santa Rosa','Turpay','Vilcabamba','Virundo','Curasco'],
            ]],
            4 => ['Arequipa', [
                'Arequipa' => ['Arequipa','Alto Selva Alegre','Cayma','Cerro Colorado','Characato','Chiguata','Jacobo Hunter','La Joya','Mariano Melgar','Miraflores','Mollebaya','Paucarpata','Pocsi','Polobaya','Quequeña','Sabandía','Sachaca','San Juan de Siguas','San Juan de Tarucani','Santa Isabel de Siguas','Santa Rita de Siguas','Socabaya','Tiabaya','Uchumayo','Vítor','Yanahuara','Yarabamba','Yura','José Luis Bustamante y Rivero'],
                'Camaná' => ['Camaná','José María Quimper','Mariano Nicolás Valcárcel','Mariscal Cáceres','Nicolás de Piérola','Ocoña','Quilca','Samuel Pastor'],
                'Caravelí' => ['Caravelí','Acarí','Atico','Atiquipa','Bella Unión','Cahuacho','Chala','Chaparra','Huanuhuanu','Jaqui','Lomas','Quicacha','Yauca'],
                'Castilla' => ['Aplao','Andagua','Ayo','Chachas','Chilcaymarca','Choco','Huancarqui','Machaguay','Orcopampa','Pampacolca','Tipán','Uñón','Uraca','Viraco'],
                'Caylloma' => ['Chivay','Achoma','Cabanaconde','Callalli','Caylloma','Coporaque','Huambo','Huanca','Ichupampa','Lari','Lluta','Maca','Madrigal','San Antonio de Chuca','Sibayo','Tapay','Tisco','Tuti','Yanque','Majes'],
                'Condesuyos' => ['Chuquibamba','Andaray','Cayarani','Chichas','Iray','Río Grande','Salamanca','Yanaquihua'],
                'Islay' => ['Mollendo','Cocachacra','Dean Valdivia','Islay','Mejía','Punta de Bombón'],
                'La Unión' => ['Cotahuasi','Alca','Charcana','Huaynacotas','Pampamarca','Puyca','Quechualla','Sayla','Tauria','Tomepampa','Toro'],
            ]],
            5 => ['Ayacucho', [
                'Huamanga' => ['Ayacucho','Acocro','Acos Vinchos','Carmen Alto','Chiara','Ocros','Pacaycasa','Quinua','San José de Ticllas','San Juan Bautista','Santiago de Pischa','Socos','Tambillo','Vinchos','Jesús Nazareno','Andrés Avelino Cáceres Dorregaray'],
                'Cangallo' => ['Cangallo','Chuschi','Los Morochucos','María Parado de Bellido','Paras','Totos'],
                'Huanca Sancos' => ['Sancos','Carapo','Sacsamarca','Santiago de Lucanamarca'],
                'Huanta' => ['Huanta','Ayahuanco','Huamanguilla','Iguaín','Luricocha','Santillana','Sivia','Llochegua','Canayre','Uchuraccay','Pucacolpa','Chaca'],
                'La Mar' => ['San Miguel','Anco','Ayna','Chilcas','Chungui','Luis Carranza','Santa Rosa','Tambo','Samugari','Anchihuay','Oronccoy'],
                'Lucanas' => ['Puquio','Aucará','Cabana','Carmen Salcedo','Chaviña','Chipao','Huac-Huas','Laramate','Leoncio Prado','Llauta','Lucanas','Ocaña','Otoca','Saisa','San Cristóbal','San Juan','San Pedro','San Pedro de Palco','Sancos','Santa Ana de Huaycahuacho','Santa Lucía'],
                'Parinacochas' => ['Coracora','Chumpi','Coronel Castañeda','Pacapausa','Pullo','Puyusca','San Francisco de Ravacayco','Upahuacho'],
                'Páucar del Sara Sara' => ['Pausa','Colta','Corculla','Lampa','Marcabamba','Oyolo','Pararca','San Javier de Alpabamba','San José de Ushua','Sara Sara'],
                'Sucre' => ['Querobamba','Belén','Chalcos','Chilcayoc','Huacaña','Morcolla','Paico','San Pedro de Larcay','San Salvador de Quije','Santiago de Paucaray','Soras'],
                'Víctor Fajardo' => ['Huancapi','Alcamenca','Apongo','Asquipata','Canaria','Cayara','Colca','Huamanquiquia','Huancaraylla','Huaya','Sarhua','Vilcanchos'],
                'Vilcas Huamán' => ['Vilcas Huamán','Accomarca','Carhuanca','Concepción','Huambalpa','Independencia','Saurama','Vischongo'],
            ]],
            6 => ['Cajamarca', [
                'Cajamarca' => ['Cajamarca','Asunción','Chetilla','Cosspán','Encañada','Jesús','Llacanora','Los Baños del Inca','Magdalena','Matara','Namora','San Juan'],
                'Cajabamba' => ['Cajabamba','Cachachi','Condebamba','Sitacocha'],
                'Celendín' => ['Celendín','Chumuch','Cortegana','Huasmín','Jorge Chávez','José Gálvez','Miguel Iglesias','Oxamarca','Sorochuco','Sucre','Utco','La Libertad de Pallán'],
                'Chota' => ['Chota','Anguía','Chadín','Chalamarca','Chiguirip','Chimban','Choropampa','Cochabamba','Conchán','Huambos','Lajas','Llama','Miracosta','Paccha','Pión','Querocoto','San Juan de Licupis','Tacabamba','Tocmoche','Chadinillo'],
                'Contumazá' => ['Contumazá','Chilete','Cupisnique','Guzmango','San Benito','Santa Cruz de Toledo','Tantarica','Yonán'],
                'Cutervo' => ['Cutervo','Callayuc','Choros','Cujillo','La Ramada','Pimpingos','Querocotillo','San Andrés de Cutervo','San Juan de Cutervo','San Luis de Lucma','Santa Cruz','Santo Domingo de la Capilla','Santo Tomás','Socota','Toribio Casanova'],
                'Hualgayoc' => ['Bambamarca','Chugur','Hualgayoc'],
                'Jaén' => ['Jaén','Bellavista','Chontali','Colasay','Huabal','Las Pirias','Pomahuaca','Pucará','Sallique','San Felipe','San José del Alto','Santa Rosa'],
                'San Ignacio' => ['San Ignacio','Chirinos','Huarango','La Coipa','Namballe','San José de Lourdes','Tabaconas'],
                'San Marcos' => ['Pedro Gálvez','Chancay','Eduardo Villanueva','Gregorio Pita','Ichocán','José Manuel Quiroz','José Sabogal'],
                'San Miguel' => ['San Miguel','Bolívar','Calquis','Catilluc','El Prado','La Florida','Llapa','Nanchoc','Niepos','San Gregorio','San Silvestre de Cochán','Tongod','Unión Agua Blanca'],
                'San Pablo' => ['San Pablo','San Bernardino','San Luis','Tumbadén'],
                'Santa Cruz' => ['Santa Cruz','Andabamba','Catache','Chancaybaños','La Esperanza','Ninabamba','Pulan','Saucepampa','Sexi','Uticyacu','Yauyucán'],
            ]],
            7 => ['Callao', [
                'Callao' => ['Callao','Bellavista','Carmen de la Legua Reynoso','La Perla','La Punta','Ventanilla','Mi Perú'],
            ]],
            8 => ['Cusco', [
                'Cusco' => ['Cusco','Ccorca','Poroy','San Jerónimo','San Sebastián','Santiago','Saylla','Wanchaq'],
                'Acomayo' => ['Acomayo','Acopia','Acos','Mosoc Llacta','Pomacanchi','Rondocan','Sangarará'],
                'Anta' => ['Anta','Ancahuasi','Cachimayo','Chinchaypujio','Huarocondo','Limatambo','Mollepata','Pucyura','Zurite'],
                'Calca' => ['Calca','Coya','Lamay','Lares','Pisac','San Salvador','Taray','Yanatile'],
                'Canas' => ['Yanaoca','Checca','Kunturkanki','Langui','Layo','Pampamarca','Quehue','Túpac Amaru'],
                'Canchis' => ['Sicuani','Checacupe','Combapata','Marangani','Pitumarca','San Pablo','San Pedro','Tinta'],
                'Chumbivilcas' => ['Santo Tomás','Capacmarca','Chamaca','Colquemarca','Livitaca','Llusco','Quiñota','Velille'],
                'Espinar' => ['Espinar','Condoroma','Coporaque','Ocoruro','Pallpata','Pichigua','Suyckutambo','Alto Pichigua'],
                'La Convención' => ['Santa Ana','Echarate','Huayopata','Maranura','Ocobamba','Quellouno','Kimbiri','Santa Teresa','Vilcabamba','Pichari','Inkawasi','Villa Virgen','Villa Kintiarina','Megantoni'],
                'Paruro' => ['Paruro','Accha','Ccapi','Colcha','Huanoquite','Omacha','Paccaritambo','Pillpinto','Yaurisque'],
                'Paucartambo' => ['Paucartambo','Caicay','Challabamba','Colquepata','Huancarani','Kosñipata'],
                'Quispicanchi' => ['Urcos','Andahuaylillas','Camanti','Ccarhuayo','Ccatca','Cusipata','Huaro','Lucre','Marcapata','Ocongate','Oropesa','Quiquijana'],
                'Urubamba' => ['Urubamba','Chinchero','Huayllabamba','Machupicchu','Maras','Ollantaytambo','Yucay'],
            ]],
            9 => ['Huancavelica', [
                'Huancavelica' => ['Huancavelica','Acobambilla','Acoria','Conayca','Cuenca','Huachocolpa','Huayllahuara','Izcuchaca','Laria','Manta','Mariscal Cáceres','Moya','Nuevo Occoro','Palca','Pilchaca','Vilca','Yauli','Ascensión','Huando'],
                'Acobamba' => ['Acobamba','Andabamba','Anta','Caja','Marcas','Paucará','Pomacocha','Rosario'],
                'Angaraes' => ['Lircay','Anchonga','Callanmarca','Ccochaccasa','Chincho','Congalla','Huanca Huanca','Huayllay Grande','Julcamarca','San Antonio de Antaparco','Santo Tomás de Pata','Secclla'],
                'Castrovirreyna' => ['Castrovirreyna','Arma','Aurahuá','Capillas','Chupamarca','Cocas','Huachos','Huamatambo','Mollepampa','San Juan','Santa Ana','Tantará','Ticrapo'],
                'Churcampa' => ['Churcampa','Anco','Chinchihuasi','El Carmen','La Merced','Locroja','Paucarbamba','San Miguel de Mayocc','San Pedro de Coris','Pachamarca','Cosme'],
                'Huaytará' => ['Huaytará','Ayaví','Córdova','Huayacundo Arma','Laramarca','Ocoyo','Pilpichaca','Querco','Quito-Arma','San Antonio de Cusicancha','San Francisco de Sangayaico','San Isidro','Santiago de Chocorvos','Santiago de Quirahuará','Santo Domingo de Capillas','Tambo'],
                'Tayacaja' => ['Pampas','Acostambo','Acraquia','Ahuaycha','Colcabamba','Daniel Hernández','Huachocolpa','Huaribamba','Ñahuimpuquio','Pazos','Quishuar','Salcabamba','Salcahuasi','San Marcos de Rocchac','Surcubamba','Tintay Puncu','Quichuas','Andaymarca','Roble','Pichos'],
            ]],
            10 => ['Huánuco', [
                'Huánuco' => ['Huánuco','Amarilis','Chinchao','Churubamba','Margos','Quisqui','San Francisco de Cayrán','San Pedro de Chaulán','Santa María del Valle','Yarumayo','Pillco Marca','Yacus','San Pablo de Pillao'],
                'Ambo' => ['Ambo','Cayna','Colpas','Conchamarca','Huácar','San Francisco','San Rafael','Tomay Kichwa'],
                'Dos de Mayo' => ['La Unión','Chuquis','Marías','Pachas','Quivilla','Ripán','Shunqui','Sillapata','Yanas'],
                'Huacaybamba' => ['Huacaybamba','Canchabamba','Cochabamba','Pinra'],
                'Huamalíes' => ['Llata','Arancay','Chavín de Pariarca','Jacas Grande','Jircan','Miraflores','Monzón','Punchao','Puños','Singa','Tantamayo'],
                'Leoncio Prado' => ['Rupa-Rupa','Daniel Alomía Robles','Hermilio Valdizán','José Crespo y Castillo','Luyando','Mariano Dámaso Beraún','Pucayacu','Castillo Grande','Pueblo Nuevo','Santo Domingo de Anda'],
                'Marañón' => ['Huacrachuco','Cholon','San Buenaventura','La Morada','Santa Rosa de Alto Yanajanca'],
                'Pachitea' => ['Panao','Chaglla','Molino','Umari'],
                'Puerto Inca' => ['Puerto Inca','Codo del Pozuzo','Honoria','Tournavista','Yuyapichis'],
                'Lauricocha' => ['Jesús','Baños','Jivia','Queropalca','Rondos','San Francisco de Asís','San Miguel de Cauri'],
                'Yarowilca' => ['Chavinillo','Cahuac','Chacabamba','Aparicio Pomares','Jacas Chico','Obas','Pampamarca','Choras'],
            ]],
            11 => ['Ica', [
                'Ica' => ['Ica','La Tinguiña','Los Aquijes','Ocucaje','Pachacútec','Parcona','Pueblo Nuevo','Salas','San José de los Molinos','San Juan Bautista','Santiago','Subtanjalla','Tate','Yauca del Rosario'],
                'Chincha' => ['Chincha Alta','Alto Larán','Chavín','Chincha Baja','El Carmen','Grocio Prado','Pueblo Nuevo','San Juan de Yanac','San Pedro de Huacarpana','Sunampe','Tambo de Mora'],
                'Nazca' => ['Nazca','Changuillo','El Ingenio','Marcona','Vista Alegre'],
                'Palpa' => ['Palpa','Llipata','Río Grande','Santa Cruz','Tibillo'],
                'Pisco' => ['Pisco','Huancano','Humay','Independencia','Paracas','San Andrés','San Clemente','Túpac Amaru Inca'],
            ]],
            12 => ['Junín', [
                'Huancayo' => ['Huancayo','Carhuacallanga','Chacapampa','Chicche','Chilca','Chongos Alto','Chupuro','Colca','Cullhuas','El Tambo','Huacrapuquio','Hualhuas','Huancan','Huasicancha','Huayucachi','Ingenio','Pariahuanca','Pilcomayo','Pucará','Quichuay','Quilcas','San Agustín','San Jerónimo de Tunán','Saño','Sapallanga','Sicaya','Santo Domingo de Acobamba','Viques'],
                'Concepción' => ['Concepción','Aco','Andamarca','Chambará','Cochas','Comas','Heroínas Toledo','Manzanares','Mariscal Castilla','Matahuasi','Mito','Nueve de Julio','Orcotuna','San José de Quero','Santa Rosa de Ocopa'],
                'Chanchamayo' => ['Chanchamayo','Perené','Pichanaqui','San Luis de Shuaro','San Ramón','Vitoc'],
                'Jauja' => ['Jauja','Acolla','Apata','Ataura','Canchayllo','Curicaca','El Mantaro','Huamalí','Huaripampa','Huertas','Janjaillo','Julcán','Leonor Ordóñez','Llocllapampa','Marco','Masma','Masma Chicche','Molinos','Monobamba','Muqui','Muquiyauyo','Paca','Paccha','Pancan','Parco','Pomacancha','Ricran','San Lorenzo','San Pedro de Chunán','Sausa','Sincos','Tunan Marca','Yauli','Yauyos'],
                'Junín' => ['Junín','Carhuamayo','Ondores','Ulcumayo'],
                'Satipo' => ['Satipo','Coviriali','Llaylla','Mazamari','Pampa Hermosa','Pangoa','Río Negro','Río Tambo','Vizcatán del Ene'],
                'Tarma' => ['Tarma','Acobamba','Huaricolca','Huasahuasi','La Unión','Palca','Palcamayo','San Pedro de Cajas','Tapo'],
                'Yauli' => ['La Oroya','Chacapalpa','Huay-Huay','Marcapomacocha','Morococha','Paccha','Santa Bárbara de Carhuacayán','Santa Rosa de Sacco','Suitucancha','Yauli'],
                'Chupaca' => ['Chupaca','Ahuac','Chongos Bajo','Huachac','Huamancaca Chico','San Juan de Iscos','San Juan de Jarpa','Tres de Diciembre','Yanacancha'],
            ]],
            13 => ['La Libertad', [
                'Trujillo' => ['Trujillo','El Porvenir','Florencia de Mora','Huanchaco','La Esperanza','Laredo','Moche','Poroto','Salaverry','Simbal','Víctor Larco Herrera'],
                'Ascope' => ['Ascope','Chicama','Chocope','Magdalena de Cao','Paiján','Rázuri','Santiago de Cao','Casa Grande'],
                'Bolívar' => ['Bolívar','Bambamarca','Condormarca','Longotea','Uchumarca','Ucuncha'],
                'Chepén' => ['Chepén','Pacanga','Pueblo Nuevo'],
                'Julcán' => ['Julcán','Calamarca','Carabamba','Huaso'],
                'Otuzco' => ['Otuzco','Agallpampa','Charat','Huaranchal','La Cuesta','Mache','Paranday','Salpo','Sinsicap','Usquil'],
                'Pacasmayo' => ['San Pedro de Lloc','Guadalupe','Jequetepeque','Pacasmayo','San José'],
                'Pataz' => ['Tayabamba','Buldibuyo','Chillia','Huancaspata','Huaylillas','Huayo','Ongón','Parcoy','Pataz','Pías','Santiago de Challas','Taurija','Urpay'],
                'Sánchez Carrión' => ['Huamachuco','Chugay','Cochorco','Curgos','Marcabal','Sanagoran','Sarín','Sartimbamba'],
                'Santiago de Chuco' => ['Santiago de Chuco','Angasmarca','Cachicadán','Mollebamba','Mollepata','Quiruvilca','Santa Cruz de Chuca','Sitabamba'],
                'Gran Chimú' => ['Cascas','Lucma','Marmot','Sayapullo'],
                'Virú' => ['Virú','Chao','Guadalupito'],
            ]],
            14 => ['Lambayeque', [
                'Chiclayo' => ['Chiclayo','Chongoyape','Eten','Eten Puerto','José Leonardo Ortiz','La Victoria','Lagunas','Monsefú','Nueva Arica','Oyotún','Pátapo','Picsi','Pimentel','Pomalca','Pucalá','Reque','Santa Rosa','Saña','Cayaltí','Tumán'],
                'Ferreñafe' => ['Ferreñafe','Cañaris','Incahuasi','Manuel Antonio Mesones Muro','Pítipo','Pueblo Nuevo'],
                'Lambayeque' => ['Lambayeque','Chóchope','Íllimo','Jayanca','Mochumí','Mórrope','Motupe','Olmos','Pacora','Salas','San José','Túcume'],
            ]],
            15 => ['Lima', [
                'Lima' => ['Lima','Ancón','Ate','Barranco','Breña','Carabayllo','Chaclacayo','Chorrillos','Cieneguilla','Comas','El Agustino','Independencia','Jesús María','La Molina','La Victoria','Lince','Los Olivos','Lurigancho','Lurín','Magdalena del Mar','Pueblo Libre','Miraflores','Pachacámac','Pucusana','Puente Piedra','Punta Hermosa','Punta Negra','Rímac','San Bartolo','San Borja','San Isidro','San Juan de Lurigancho','San Juan de Miraflores','San Luis','San Martín de Porres','San Miguel','Santa Anita','Santa María del Mar','Santa Rosa','Santiago de Surco','Surquillo','Villa El Salvador','Villa María del Triunfo'],
                'Barranca' => ['Barranca','Paramonga','Pativilca','Supe','Supe Puerto'],
                'Cajatambo' => ['Cajatambo','Copa','Gorgor','Huancapón','Manás'],
                'Canta' => ['Canta','Arahuay','Huamantanga','Huaros','Lachaqui','San Buenaventura','Santa Rosa de Quives'],
                'Cañete' => ['San Vicente de Cañete','Asia','Calango','Cerro Azul','Chilca','Coayllo','Imperial','Lunahuaná','Mala','Nuevo Imperial','Pacarán','Quilmaná','San Antonio','San Luis','Santa Cruz de Flores','Zúñiga'],
                'Huaral' => ['Huaral','Atavillos Alto','Atavillos Bajo','Aucallama','Chancay','Ihuarí','Lampián','Pacaraos','San Miguel de Acos','Santa Cruz de Andamarca','Sumbilca','Veintisiete de Noviembre'],
                'Huarochirí' => ['Matucana','Antioquia','Callahuanca','Carampoma','Chicla','Cuenca','Huachupampa','Huanza','Huarochirí','Lahuaytambo','Langa','Laraos','Mariatana','Ricardo Palma','San Andrés de Tupicocha','San Antonio','San Bartolomé','San Damián','San Juan de Iris','San Juan de Tantaranche','San Lorenzo de Quinti','San Mateo','San Mateo de Otao','San Pedro de Casta','San Pedro de Huancayre','Sangallaya','Santa Cruz de Cocachacra','Santa Eulalia','Santiago de Anchucaya','Santiago de Tuna','Santo Domingo de los Olleros','Surco'],
                'Huaura' => ['Huacho','Ámbar','Caleta de Carquín','Checras','Hualmay','Huaura','Leoncio Prado','Paccho','Santa Leonor','Santa María','Sayán','Vegueta'],
                'Oyón' => ['Oyón','Andajes','Caujul','Cochamarca','Naván','Pachangara'],
                'Yauyos' => ['Yauyos','Alis','Ayauca','Ayaviri','Azángaro','Cacra','Carania','Catahuasi','Chocos','Cochas','Colonia','Hongos','Huampará','Huancaya','Huangáscar','Huantán','Huañec','Laraos','Lincha','Madean','Miraflores','Omas','Putinza','Quinches','Quinocay','San Joaquín','San Pedro de Pilas','Tanta','Tauripampa','Tomas','Tupe','Viñac','Vitis'],
            ]],
            16 => ['Loreto', [
                'Maynas' => ['Iquitos','Alto Nanay','Fernando Lores','Indiana','Las Amazonas','Mazán','Napo','Punchana','Torres Causana','Belén','San Juan Bautista','Teniente Manuel Clavero'],
                'Alto Amazonas' => ['Yurimaguas','Balsapuerto','Jeberos','Lagunas','Santa Cruz','Teniente César López Rojas'],
                'Loreto' => ['Nauta','Parinari','Tigre','Trompeteros','Urarinas'],
                'Mariscal Ramón Castilla' => ['Ramón Castilla','Pebas','Yavari','San Pablo'],
                'Requena' => ['Requena','Alto Tapiche','Capelo','Emilio San Martín','Maquia','Puinahua','Saquena','Soplin','Tapiche','Jenaro Herrera','Yaquerana'],
                'Ucayali' => ['Contamana','Inahuaya','Padre Márquez','Pampa Hermosa','Sarayacu','Vargas Guerra'],
                'Datem del Marañón' => ['Barranca','Cahuapanas','Manseriche','Morona','Pastaza','Andoas'],
                'Putumayo' => ['Putumayo','Rosa Panduro','Teniente Manuel Clavero','Yaguas'],
            ]],
            17 => ['Madre de Dios', [
                'Tambopata' => ['Tambopata','Inambari','Las Piedras','Laberinto'],
                'Manu' => ['Manu','Fitzcarrald','Madre de Dios','Huepetuhe'],
                'Tahuamanu' => ['Iñapari','Iberia','Tahuamanu'],
            ]],
            18 => ['Moquegua', [
                'Mariscal Nieto' => ['Moquegua','Carumas','Cuchumbaya','Samegua','San Cristóbal','Torata'],
                'General Sánchez Cerro' => ['Omate','Chojata','Coalaque','Ichuña','La Capilla','Lloque','Matalaque','Puquina','Quinistaquillas','Ubinas','Yunga'],
                'Ilo' => ['Ilo','El Algarrobal','Pacocha'],
            ]],
            19 => ['Pasco', [
                'Pasco' => ['Chaupimarca','Huachón','Huariaca','Huayllay','Ninacaca','Pallanchacra','Paucartambo','San Francisco de Asís de Yarusyacán','Simon Bolívar','Ticlacayán','Tinyahuarco','Vicco','Yanacancha'],
                'Daniel Alcides Carrión' => ['Yanahuanca','Chacayán','Goyllarisquizga','Paucar','San Pedro de Pillao','Santa Ana de Tusi','Tapuc','Vilcabamba'],
                'Oxapampa' => ['Oxapampa','Chontabamba','Huancabamba','Palcazú','Pozuzo','Puerto Bermúdez','Villa Rica','Constitución'],
            ]],
            20 => ['Piura', [
                'Piura' => ['Piura','Castilla','Catacaos','Cura Mori','El Tallán','La Arena','La Unión','Las Lomas','Tambo Grande','Veintiséis de Octubre'],
                'Ayabaca' => ['Ayabaca','Frías','Jililí','Lagunas','Montero','Pacaipampa','Paimas','Sapillica','Sicchez','Suyo'],
                'Huancabamba' => ['Huancabamba','Canchaque','El Carmen de la Frontera','Huarmaca','Lalaquiz','San Miguel de El Faique','Sóndor','Sondorillo'],
                'Morropón' => ['Chulucanas','Buenos Aires','Chalaco','La Matanza','Morropón','Salitral','San Juan de Bigote','Santa Catalina de Mossa','Santo Domingo','Yamango'],
                'Paita' => ['Paita','Amotape','Arenal','Colán','La Huaca','Tamarindo','Vichayal'],
                'Sullana' => ['Sullana','Bellavista','Ignacio Escudero','Lancones','Marcavelica','Miguel Checa','Querecotillo','Salitral'],
                'Talara' => ['Pariñas','El Alto','La Brea','Lobitos','Los Órganos','Máncora'],
                'Sechura' => ['Sechura','Bellavista de la Unión','Bernal','Cristo Nos Valga','Vice','Rinconada Llicuar'],
            ]],
            21 => ['Puno', [
                'Puno' => ['Puno','Acora','Amantaní','Atuncolla','Capachica','Chucuito','Coata','Huata','Mañazo','Paucarcolla','Pichacani','Platería','San Antonio','Tiquillaca','Vilque'],
                'Azángaro' => ['Azángaro','Achaya','Arapa','Asillo','Caminaca','Chupa','José Domingo Choquehuanca','Muñani','Potoni','Saman','San Antón','San José','San Juan de Salinas','Santiago de Pupuja','Tirapata'],
                'Carabaya' => ['Macusani','Ajoyani','Ayapata','Coasa','Corani','Crucero','Ituata','Ollachea','San Gabán','Usicayos'],
                'Chucuito' => ['Juli','Desaguadero','Huacullani','Kelluyo','Pisacoma','Pomata','Zepita'],
                'El Collao' => ['Ilave','Capazo','Pilcuyo','Santa Rosa','Conduriri'],
                'Huancané' => ['Huancané','Cojata','Huatasani','Inchupalla','Pusi','Rosaspata','Taraco','Vilque Chico'],
                'Lampa' => ['Lampa','Cabanilla','Calapuja','Nicasio','Ocuviri','Palca','Paratia','Pucará','Santa Lucía','Vilavila'],
                'Melgar' => ['Ayaviri','Antauta','Cupi','Llalli','Macari','Nuñoa','Orurillo','Santa Rosa','Umachiri'],
                'Moho' => ['Moho','Conima','Huayrapata','Tilali'],
                'San Antonio de Putina' => ['Putina','Ananea','Pedro Vilca Apaza','Quilcapuncu','Sina'],
                'San Román' => ['Juliaca','Cabana','Cabanillas','Caracoto','San Miguel'],
                'Sandia' => ['Sandia','Cuyocuyo','Limbani','Patambuco','Phara','Quiaca','San Juan del Oro','Yanahuaya','Alto Inambari','San Pedro de Putina Punco'],
                'Yunguyo' => ['Yunguyo','Anapia','Copani','Cuturapi','Ollaraya','Tinicachi','Unicachi'],
            ]],
            22 => ['San Martín', [
                'Moyobamba' => ['Moyobamba','Calzada','Habana','Jepelacio','Soritor','Yantalo'],
                'Bellavista' => ['Bellavista','Alto Biavo','Bajo Biavo','Huallaga','San Pablo','San Rafael'],
                'El Dorado' => ['San José de Sisa','Agua Blanca','San Martín','Santa Rosa','Shatoja'],
                'Huallaga' => ['Saposoa','Alto Saposoa','El Eslabón','Piscoyacu','Sacanche','Tingo de Saposoa'],
                'Lamas' => ['Lamas','Alonso de Alvarado','Barranquita','Caynarachi','Cuñumbuqui','Pinto Recodo','Rumisapa','San Roque de Cumbaza','Shanao','Tabalosos','Zapatero'],
                'Mariscal Cáceres' => ['Juanjuí','Campanilla','Huicungo','Pachiza','Pajarillo'],
                'Picota' => ['Picota','Buenos Aires','Caspisapa','Pilluana','Pucacaca','San Cristóbal','San Hilarión','Shamboyacu','Tingo de Ponasa','Tres Unidos'],
                'Rioja' => ['Rioja','Awajún','Elías Soplín Vargas','Nueva Cajamarca','Pardo Miguel','Posic','San Fernando','Yorongos','Yuracyacu'],
                'San Martín' => ['Tarapoto','Alberto Leveau','Cacatachi','Chazuta','Chipurana','El Porvenir','Huimbayoc','Juan Guerra','La Banda de Shilcayo','Morales','Papaplaya','San Antonio','Sauce','Shapaja'],
                'Tocache' => ['Tocache','Nuevo Progreso','Pólvora','Shunte','Uchiza'],
            ]],
            23 => ['Tacna', [
                'Tacna' => ['Tacna','Alto de la Alianza','Calana','Ciudad Nueva','Inclán','Pachía','Palca','Pocollay','Sama','Coronel Gregorio Albarracín Lanchipa','La Yarada-Los Palos'],
                'Candarave' => ['Candarave','Cairani','Camilaca','Curibaya','Huanuara','Quilahuani'],
                'Jorge Basadre' => ['Locumba','Ilabaya','Ite'],
                'Tarata' => ['Tarata','Héroes Albarracín','Estique','Estique-Pampa','Sitajara','Susapaya','Tarucachi','Ticaco'],
            ]],
            24 => ['Tumbes', [
                'Tumbes' => ['Tumbes','Corrales','La Cruz','Pampas de Hospital','San Jacinto','San Juan de la Virgen'],
                'Contralmirante Villar' => ['Zorritos','Casitas','Canoas de Punta Sal'],
                'Zarumilla' => ['Zarumilla','Aguas Verdes','Matapalo','Papayal'],
            ]],
            25 => ['Ucayali', [
                'Coronel Portillo' => ['Callería','Campoverde','Iparia','Masisea','Yarinacocha','Nueva Requena','Manantay'],
                'Atalaya' => ['Raymondi','Sepahua','Tahuanía','Yurúa'],
                'Padre Abad' => ['Padre Abad','Irazola','Curimaná','Neshuya','Alexander Von Humboldt'],
                'Purús' => ['Purús'],
            ]],
        ];
    }
}
