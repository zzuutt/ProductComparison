# Product Comparison 
==================
author: Zzuutt <zzuutt34 at free.fr>
for Thelia >= 2.2.0

Add a comparator on your website

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is ProductComparison.
* Activate it in your thelia administration panel

## Usage

In the tools menu, a new entry is displayed  'product comparison' .

In the front office, an integration is provided for the default template. It uses hooks, so it's activated by default.

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

