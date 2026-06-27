# Animal rating

Vote pour ton animal préféré

## Requirements

- PHP 8.2+
- Composer

## Lancer l'application en local

```bash
composer install

php bin/console importmap:install

php bin/console doctrine:migrations:migrate --no-interaction

php -S localhost:8000 -t public/
```

### Partie 2

La Partie 2 (un votant garde jusqu'à 3 animaux) est sur la branche `part_2`. Pour la tester, place-toi
sur la branche puis rejoue les migrations, le reste ne change pas.

```bash
git switch part_2
php bin/console doctrine:migrations:migrate --no-interaction
```

## Tests

```bash
php vendor/bin/phpunit
```

Les tests d'intégration reconstruisent un schéma SQLite vierge à la volée (`AbstractDatabaseTestCase`),
donc rien à monter à la main avant de les lancer.

- `NameNormalizerTest` : normalisation (minuscule + trim + accents).
- `VoteRecorderTest` : un second vote du même votant écrase le premier, noms stockés normalisés.
- `VoteRepositoryTest` : classement des animaux par citations + note moyenne.

## Pages

- `/` : formulaire de vote (nom, animal, score de 0 à 100). Public.
- `/admin` : classement des animaux (nombre de citations + note moyenne, le plus cité en
  premier). Protégé par HTTP Basic.
    - identifiant : `admin`
    - mot de passe : `secret`

## Choix techniques

- Les noms sont stockés en minuscule + trim (`NameNormalizer`) : `Jean`, `jean` et ` JEAN ` sont donc
la même personne, et les votes s'agrègent quelle que soit la saisie.

- Une contrainte d'unicité sur `person_name` fait qu'un nouveau vote écrase le précédent.

- À l'affichage, on recapitalise avec le filtre Twig `capitalize`. Du coup `jean-pierre` ressort en `Jean-pierre`.

- Les noms n'acceptent que lettres, espaces, tirets et apostrophes (`NameFormat`), le score est un entier
0–100.

- L'admin est derrière un HTTP Basic et lit ses stats via un petit DTO `AnimalStat`.

- Le front tourne sur Pico CSS en classless.
