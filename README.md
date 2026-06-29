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
- `VoteRecorderTest` : re-voter un animal met à jour son score, et un votant ne garde que 3 animaux.
- `VoteRepositoryTest` : classement des animaux par citations + note moyenne.

## Pages

- `/` : formulaire de vote (nom, animal, score de 0 à 100). Public.
- `/admin` : classement des animaux (nombre de citations + note moyenne, le plus cité en
  premier). Protégé par HTTP Basic.
    - identifiant : `admin`
    - mot de passe : `secret`

## Choix techniques

- Les noms sont stockés en minuscule + trim (`NameNormalizer`), donc `Jean`, `jean` et ` JEAN ` sont
la même personne et les votes s'agrègent quelle que soit la saisie.

- Un votant garde jusqu'à 3 animaux. Le dernier saisi est toujours conservé. Au-delà de 3, on retire
  celui qui a le plus petit score parmi les autres. Re-voter un même animal met juste à jour son score.
  L'unicité est posée sur le couple `(person_name, animal_name)`.

- À l'affichage, on recapitalise avec le filtre Twig `capitalize`. Du coup `jean-pierre` ressort en `Jean-pierre`.

- Les noms n'acceptent que lettres, espaces, tirets et apostrophes (`NameFormat`). Le score est un entier
de 0 à 100.

- L'admin est derrière un HTTP Basic et lit ses stats via un petit DTO `AnimalStat`.

- Le front tourne sur Pico CSS en classless.
