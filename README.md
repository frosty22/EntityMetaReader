EntityMetaReader
================
Verze Alpha

Rozšíření podpory entit o vlastní anotace za pomocí Doctrine/Annotations.


EntityReader
------------

Základním objektem je EntityReader, který přijímá instanci Doctrine\Common\Annotations\Reader. Tento objekt posléze vrací pole objektů ColumnReader pro každou property dané entity.


ColumnReader
------------

Objekt reprezentující property entity. Tento objekt Vám umožňuje získávat objekty reprezentující konkrétní anotace a dále je možné pomocí něj získávát základní informace o dané property.

- getAnnotation(...) - základní metoda, které vrací danou anotaci či v případě neexistnce může vytvářet anotaci s defaultními hodnotami
- isValueType() - vrací TRUE pokud je property sloupec reprezentující nějakou hodnotu, tj. má anotaci Column
- isEntityType() - vrací TRUE pokud je property reference na jinou entitu (= vazba OneToOne, ManyToOne)
- isCollectionType() - vrací TRUE pokud je property kolekce entit (= vazby ManyToMany, OneToMany)
- getTargetEntity() - pokud je property vazba na jinou entitu, vrací název této cílové entity

Celý objekt implementuje rozhraní Iterator, tudíž je možné nad ním iterovat, čímž budete procházet jednotlivé anotace property.


Mapping
-------

Knihovna obsahuje několik základních objektů reprezentující anotace:

- Access - objekt zastupující anotaci definující přístup k dané property - zápis/čtení dané property, může být využito například pro generované datagridy, formuláře.

- Name - triviální human-read název property, například pro nadpisy sloupců datagridu, label pro formulář atd.


Příklad použití
---------------

```php
use EntityMetaReader\Mapping as EMR;
use Doctrine\ORM\Mapping as ORM;

class Product extends \Ale\Entities\IdentifiedEntity
{

	/**
	 * @EMR\Name("Název produktu")
	 * @ORM\Column(type="string")
	 * @var string
	 */
	protected $name;


	/**
	 * @EMR\Name("Provize")
	 * @EMR\Access(read="admin", write=false)
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	protected $commission;


	/**
	 * @EMR\Access(read=false)
	 * @ORM\Column(type="integer")
	 * @var int
	 */
	protected $someInternal;


}

$user = new Nette\Security\User(...);
$reader = new EntityReader(...);
$columns = $reader->getEntityColumns("Product");

foreach ($columns as $columnReader) {
	$access = $columnReader->getAnnotation('EntityMetaReader\Mapping\Access', TRUE);
	/** @var EntityMetaReader\Mapping\Access $access */

	echo $access->checkReadAccess($user);  // Může uživatel číst property
	echo $access->checkWriteAccess($user); // Může uživatel zapisovat property

	$name = $column->getAnnotation('EntityMetaReader\Mapping\Name', TRUE, $columnReader->getName());
	/** @var EntityMetaReader\Mapping\Name */

	echo $name; // Vypiš human-read název property, pokud není vytvoří objekt Name s defaultní hodnotou názvu property

}
```