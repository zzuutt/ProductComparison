# Product Comparison 

author: Zzuutt <zzuutt34@free.fr>

for Thelia >= 2.3.0

Add a comparator on your website

if you use this module, notify me by email thank you

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ProductComparison.
* Activate it in your thelia administration panel

## Usage

In the tools menu, a new entry is displayed  'product comparison' .

In the front office, an integration is provided for the default template. It uses hooks, so it's activated by default.

L'utilisation est simple:

Sur votre site vous voulez offrir la possibilité à vos client de comparer certains produits exemple: des téléviseurs
- Coté back 

1- vous créez des caractéristiques (taille, consommation, couleur....)

2- vous les rattachez à un gabarit

3- sur la page de chaque produit, vous appliquez le gabarit et renseignez les caractéristiques

4- vous activez le module 'Comparateur de produits' (à l'activation toutes les caractérisques et gabarit sont copiés)

5- dans le menu Outils, selectionnez 'Comparateur de produits'

6- classez, gardez les caractéristiques que vous voulez afficher sur la page comparateur

7- dans l'onglet configuration, selectionnez les catégories et gabarits

8- c'est fini, le comparateur est affiché !


## Hook

It uses default hooks.

In comparator page, I added:
- comparator.top
- comparator.bottom
- comparator.stylesheet
- comparator.after-javascript-include
- comparator.javascript-initialization

## Loop

[product-comparison]

### Input arguments

|Argument |Description |
|---      |--- |
|**id** | A single or a list of ids. |
|**feature_id** | A single or a list of ids. |
|**template_id** | A single or a list of ids. |
|**exclude_template** | A single or a list of ids.|
|**order** | id, id-reverse, feature_id, feature_id-reverse, template_id, templade_id-reverse, manual, manual-reverse |
|**group** | template, feature |

### Output arguments

|Variable   |Description |
|---        |--- |
|$ID    | id |
|$FEATURE_ID    | id of the feature |
|$TEMPLATE_ID    | id of the template |
|$POSITION    | position |

[product-comparison-feature]

### Input arguments

|Argument |Description |
|---      |--- |
|**id** | A single or a list of ids. |
|**template** | A single or a list of ids. |
|**exclude_template** | A single or a list of ids.|
|**order** | id, id-reverse, manual, manual-reverse |

### Output arguments

|Variable   |Description |
|---        |--- |
|$ID    | id |
|$IS_TRANSLATED    |  |
|$LOCALE    |  |
|$TITLE    | title of the feature |
|$POSITION    | position |

### Exemple
```
{loop name="product-comparison-list" type="product-comparison" order='manual' template_id="$comparator_template"}
...
{/loop}
```

```
{loop type='product-comparison-feature' name='feature-notin-list' order='id' exclude_template=$template_id template=$template_id}
....
{/loop}
```

### TARTEAUCITRON
Si vous utilisez [Tarteaucitron](https://tarteaucitron.io) pour le RGPD, rajoutez ce service
```javascript
// comparator
tarteaucitron.services.comparator = {
    "key": "comparator",
    "type": "other",
    "name": "Comparator",
    "needConsent": true,
    "cookies": ['thelia_comparator'],
    "js": function () {
        "use strict";
        if($("button[id$='comparator']").length){
            location.reload(true);
        }

    },
    "fallback": function () {
        "use strict";
        var id = 'comparator';
        tarteaucitron.fallback(['btn-comparator'], tarteaucitron.engage(id));
    }
};
```
