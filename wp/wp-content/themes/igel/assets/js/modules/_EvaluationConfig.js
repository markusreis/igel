import {EvaluationStep} from "./_EvaluationStep";
import {EvaluationField} from "./_EvaluationField";

export const evaluationSteps = [

    new EvaluationStep({
                           name  : 'branchSelect',
                           title : 'Bitte wählen Sie den passenden Objekt-Typ',
                           fields: [
                               new EvaluationField({
                                                       type   : 'radio',
                                                       name   : 'eval-type',
                                                       onError: 'Bitte wählen Sie einen Objekt-Typ',
                                                       options: [
                                                           {name: 'Einfamilienhaus', value: 'house', icon: 'house'},
                                                           {name: 'Wohnung', value: 'apartment', icon: 'houses'},
                                                           {name: 'Grunstück', value: 'property', icon: 'area-2'}
                                                       ]
                                                   }),
                           ],
                       }),

    new EvaluationStep({
                           branches: ['house'],
                           name    : 'houseType',
                           title   : 'Um welche Art Haus handelt es sich?',
                           fields  : [
                               new EvaluationField({
                                                       name   : 'eval-housetype',
                                                       type   : 'radio',
                                                       onError: 'Bitte wählen Sie eine Hausart',
                                                       options: [
                                                           {name: 'Einfamilienhaus', icon: ''},
                                                           {name: 'Doppelhaushälfte', icon: ''},
                                                           {name: 'Reihenhaus', icon: ''}
                                                       ]
                                                   })
                           ],
                       }),
    new EvaluationStep({
                           branches: ['property'],
                           name    : 'propType',
                           title   : 'Grundstücksform',
                           fields  : [
                               new EvaluationField({
                                                       type   : 'radio',
                                                       name   : 'eval-propform',
                                                       onError: 'Bitte wählen Sie eine Grundstücksform',
                                                       options: [
                                                           {name: 'quadratisch'},
                                                           {name: 'rechteckig'},
                                                           {name: 'asymmetrische Form'}
                                                       ]
                                                   })
                           ],
                       }),
    new EvaluationStep({
                           branches: ['house'],
                           name    : 'area-house',
                           title   : 'Wohn- und Grundstücksfläche',
                           fields  : [
                               new EvaluationField({
                                                       name   : 'eval-area-house',
                                                       onError: 'Bitte geben Sie eine Grundstücksfläche an',
                                                       regex  : new RegExp("[0-9]{2,}"),
                                                       type   : 'number',
                                                       label  : 'Grundstücksfläche (m<sup>2</sup>)',
                                                   }),
                               new EvaluationField({
                                                       name   : 'eval-living-area-house',
                                                       onError: 'Bitte geben Sie eine Wohnfläche an',
                                                       regex  : new RegExp("[0-9]{2,}"),
                                                       type   : 'number',
                                                       label  : 'Wohnfläche (m<sup>2</sup>)',
                                                   }),
                           ],
                       }),
    new EvaluationStep({
                           branches: ['apartment'],
                           name    : 'area-apartment',
                           title   : 'Wohnfläche',
                           fields  : [
                               new EvaluationField({
                                                       name   : 'eval-living-area',
                                                       onError: 'Bitte geben Sie eine Wohnfläche an',
                                                       regex  : new RegExp("[0-9]{2,}"),
                                                       type   : 'number',
                                                       label  : 'Wohnfläche (m<sup>2</sup>)',
                                                   }),
                           ],
                       }),
    new EvaluationStep({
                           branches: ['property'],
                           name    : 'area-property',
                           title   : 'Grundstücksfläche',
                           fields  : [
                               new EvaluationField({
                                                       name   : 'eval-area-prop',
                                                       onError: 'Bitte geben Sie eine Grundstücksfläche an',
                                                       regex  : new RegExp("[0-9]{2,}"),
                                                       type   : 'number',
                                                       label  : 'Grundstücksfläche (m<sup>2</sup>)',
                                                   }),
                           ],
                       }),
    new EvaluationStep({
                           branches: ['house', 'apartment'],
                           name    : 'area',
                           title   : 'Details zum Objekt',
                           fields  : [
                               new EvaluationField({
                                                       name   : 'eval-year',
                                                       type   : 'number',
                                                       onError: 'Bitte geben Sie ein Baujahr an',
                                                       regex  : new RegExp("[0-9]{2,}"),
                                                       label  : 'Baujahr',
                                                   }),
                               new EvaluationField({
                                                       name   : 'eval-car',
                                                       type   : 'radio',
                                                       label  : 'Auto-Abstellplatz',
                                                       onError: 'Bitte wählen Sie einen Auto-Abstellplatz',
                                                       options: [
                                                           {name: 'kein Parkplatz'},
                                                           {name: 'nummerierter Autoabstellplatz'},
                                                           {name: 'Carport'},
                                                           {name: 'Garage'},
                                                       ]
                                                   }),
                           ],
                       }),
    new EvaluationStep({
                           branches: ['property'],
                           name    : 'topo',
                           title   : 'Topographie',
                           fields  : [
                               new EvaluationField({
                                                       name   : 'eval-topo',
                                                       type   : 'radio',
                                                       onError: 'Bitte wählen Sie eine Topographie',
                                                       options: [
                                                           {name: 'flach'},
                                                           {name: 'leichtes Gefälle'},
                                                           {name: 'starkes Gefälle'}
                                                       ]
                                                   })
                           ],
                       }),
    new EvaluationStep({
                           name  : 'address',
                           title : 'Wie lautet die Adresse ihrer Immobilie?',
                           fields: [
                               new EvaluationField({
                                                       name   : 'eval-street',
                                                       type   : 'text',
                                                       onError: 'Bitte geben Sie eine Straße an',
                                                       label  : 'Straße und Hausnummer',
                                                   }),
                               new EvaluationField({
                                                       name   : 'eval-plz',
                                                       type   : 'text',
                                                       onError: 'Bitte geben Sie eine PLZ an',
                                                       label  : 'PLZ',
                                                   }),
                               new EvaluationField({
                                                       name   : 'eval-place',
                                                       type   : 'text',
                                                       onError: 'Bitte geben Sie einen Ort an',
                                                       label  : 'Ort',
                                                   }),
                           ],
                       }),
    new EvaluationStep({
                           name  : 'date',
                           title : 'Wann wollen Sie verkaufen?',
                           fields: [
                               new EvaluationField({
                                                       name   : 'eval-when',
                                                       type   : 'radio',
                                                       onError: 'Bitte wählen Sie eine mögliche Verkaufszeit',
                                                       options: [
                                                           {name: 'binnen 6 Monaten'},
                                                           {name: 'binnen 12 Monaten'},
                                                           {name: 'binnen 24 Monaten'},
                                                       ]
                                                   })
                           ],
                       }),
    new EvaluationStep({
                           name  : 'person',
                           title : 'Wie können wir Sie kontaktieren?',
                           fields: [
                               new EvaluationField({
                                                       name   : 'eval-title',
                                                       type   : 'text',
                                                       onError: 'Bitte geben Sie eine Anrede an',
                                                       label  : 'Anrede',
                                                   }),
                               new EvaluationField({
                                                       name   : 'eval-name',
                                                       type   : 'text',
                                                       onError: 'Bitte geben Sie einen Vor- und Nachnamen an',
                                                       label  : 'Vor- und Nachname',
                                                   }),
                               new EvaluationField({
                                                       name   : 'eval-phone',
                                                       type   : 'text',
                                                       onError: 'Bitte geben Sie eine Telefonnummer an',
                                                       label  : 'Telefonnummer',
                                                   }),
                               new EvaluationField({
                                                       name   : 'eval-mail',
                                                       type   : 'text',
                                                       onError: 'Bitte geben Sie eine E-Mail Adresse an',
                                                       label  : 'E-Mail Adresse',
                                                   }),
                               new EvaluationField({
                                                       name   : 'eval-privacy',
                                                       type   : 'checkbox',
                                                       onError: 'Bitte akzeptieren Sie die Datenschutzerklärung',
                                                       label  : 'Ich stimme der <a href="https://www.igel-immobilien.at/datenschutzerklaerung">Datenschutzerklärung</a> und dem Erhalt von E-Mails von Igel Immobilien, und für den Fall dass ich eine Telefonnummer angegeben habe, der Kontaktaufnahme per Telefon zu. Ein Rechtsanspruch auf die Ausführung der Bewertung besteht nicht.',
                                                   }),
                           ],
                       }),
]