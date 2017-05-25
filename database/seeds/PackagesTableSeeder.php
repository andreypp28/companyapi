<?php

/**
 * PackagesTableSeeder - Insert info into database.
 *
 * @author   Agustí Dosaiguas
 */
class PackagesTableSeeder extends BaseTableSeeder
{
    protected $table = "packages";

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->deleteTable();

        $data = [

            [
                'name'     => "Package1",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 1
            ],
            [
                'name'     => "Package2",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 1
            ],
            [
                'name'     => "Package3",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 1
            ],
            [
                'name'     => "Package4",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 1
            ],
            [
                'name'     => "Package5",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 1
            ],
            [
                'name'     => "Package1",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package2",
                'information' => 'Catalunya és un territori històric format originalment a partir dels comtats que formaven la Marca Hispànica, al nord-est de la península Ibèrica, en temps de Carlemany.',
                'companyId' => 20
            ],
            [
                'name'     => "Package3",
                'information' => 'Avui en dia, el mot Catalunya s\'empra sobretot per referir-se a la Comunitat Autònoma de Catalunya, a Espanya, i la Catalunya del Nord, que forma el Departament dels Pirineus Orientals a França.',
                'companyId' => 20
            ],
            [
                'name'     => "Package4",
                'information' => 'Sobre la base llatina es van superposar dos prestrats principals en la formació del que serà Catalunya: el visigot i l\'àrab,',
                'companyId' => 20
            ],
            [
                'name'     => "Package5",
                'information' => 'amb diferent grau d\'incidència segons la part de Catalunya (nova o vella) i diversa influència en el temps d\'estada i la composició social.',
                'companyId' => 20
            ],
            [
                'name'     => "Package6",
                'information' => 'Catalunya triomfant, tornarà a ser rica i plena. Endarrere aquesta gent tan ufana i tan superba.',
                'companyId' => 20
            ],
            [
                'name'     => "Package7",
                'information' => 'Bon cop de falç! Bon cop de falç, Defensors de la terra! Bon cop de falç!',
                'companyId' => 20
            ],
            [
                'name'     => "Package8",
                'information' => 'Ara és hora, segadors. Ara és hora d\'estar alerta. Per quan vingui un altre juny esmolem ben bé les eines.
',
                'companyId' => 20
            ],
            [
                'name'     => "Package9",
                'information' => 'Que tremoli l\'enemic en veient la nostra ensenya. Com fem caure espigues d\'or, quan convé seguem cadenes.',
                'companyId' => 20
            ],
            [
                'name'     => "Package10",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package11",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package12",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package13",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package14",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package15",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package16",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package17",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package18",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package19",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package20",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package21",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package22",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package23",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package24",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package25",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package26",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package27",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package28",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package29",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package30",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package31",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package32",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package33",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package34",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ],
            [
                'name'     => "Package35",
                'information' => 'This is the default package (then you can add some modification based on the others',
                'companyId' => 20
            ]
        ];

        $this->loadTable($data);
    }
}
