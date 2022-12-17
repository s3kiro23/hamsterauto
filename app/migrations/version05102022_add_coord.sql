ALTER TABLE `settings` ADD `coordinates` JSON NOT NULL DEFAULT '{ \"aflo_aja\": { \"lat\": 41.966247516429775, \"lng\": 8.814473884655465, \"addr\": \"Centre professionnel A Stella, Lieu-dit Effrico, 20167 Sarrola-Carcopino\" }, \"aflo_bia\": { \"lat\": 42.64775875126715, \"lng\": 9.436714339953756, \"addr\": \"Centre professionnel A Murza, Chem. de Canale, 20600 Furiani\" } }' AFTER `nb_lifts`;

COMMIT;

/*
{
    "aflo_aja": {
        "lat": 41.966247516429775,
        "lng": 8.814473884655465,
        "addr": "Centre professionnel A Stella, Lieu-dit Effrico, 20167 Sarrola-Carcopino"
    },
    "aflo_bia": {
        "lat": 42.64775875126715,
        "lng": 9.436714339953756,
        "addr": "Centre professionnel A Murza, Chem. de Canale, 20600 Furiani"
    }
}
*/