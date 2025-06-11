# MTX Backend API

Application backend développée avec Symfony et conteneurisée avec Docker pour un déploiement et un développement simplifiés.

![Capture d’écran du 2025-06-11 19-32-56](https://github.com/user-attachments/assets/42f8eb4e-886a-4cc7-a3ed-6d13c87a1504)
![Capture d’écran du 2025-06-11 19-38-04](https://github.com/user-attachments/assets/6bdf2786-0147-48e6-b6d3-779d79a481d8)


## 🔧 Prérequis

Avant de commencer, assurez-vous d'avoir installé sur votre environnement :

- **Docker** (version 20.10+) et **Docker Compose** (version 2.0+)
- **Make** pour l'exécution des commandes automatisées
- **Git** pour cloner le repository

## 🐳 Pourquoi Docker ?

L'utilisation de Docker apporte de nombreux avantages :

- **Isolation complète** : Environnement de développement identique pour toute l'équipe
- **Portabilité** : Fonctionne sur Windows, macOS et Linux
- **Déploiement simplifié** : Même environnement en développement et en production
- **Gestion des dépendances** : Plus de conflits entre versions de PHP, MySQL, etc.
- **Setup rapide** : Installation en une seule commande
- **Scalabilité** : Facilite la montée en charge et l'orchestration

## 🚀 Quick Start

```bash
# Cloner le repository
git clone [https://github.com/votre-username/mtx-backend.git](https://github.com/DanihStephane/loan-comparator-backend.git)
cd loan-comparator-backend

# Démarrer l'application
make up

# Installer les dépendances (première fois)
make composer-install
```

L'application sera accessible sur :
- **API Backend** : http://localhost:8080

## 🛠️ Stack Technique

### Backend
- **PHP 8.2** avec PHP-FPM
- **Symfony 7** (Framework PHP moderne)
- **Nginx** (Serveur web/proxy)

### DevOps
- **Docker & Docker Compose** (Conteneurisation)
- **Makefile** (Automatisation des tâches)

### Qualité de Code
- **PHPStan** (Analyse statique)
- **PHP_CodeSniffer** (Standards de codage PSR-12)
- **PHPUnit** (Tests unitaires et fonctionnels)

## 📁 Architecture Docker

```
mtx-backend/
├── docker/
│   └── nginx/
│       └── default.conf
├── compose.yml           # Configuration Docker Compose
├── Dockerfile            # Image PHP personnalisée
└── Makefile              # Commandes automatisées
```

### Services Docker

| Service | Container | Port | Description |
|---------|-----------|------|-------------|
| **php** | `mtx_backend` | 9000 | Application Symfony + PHP-FPM |
| **nginx** | `mtx_nginx` | 8080→80 | Serveur web et reverse proxy |

## 📝 Commandes Disponibles

### Gestion Docker
```bash
make up                # Démarre tous les services
make down              # Arrête tous les services
make restart           # Redémarre tous les services
make build             # Reconstruit les images Docker
make logs              # Affiche les logs en temps réel
```

### Gestion PHP/Composer
```bash
make composer-install  # Installe les dépendances PHP
make composer-update   # Met à jour les dépendances
make sh                # Accès shell au container PHP
```

### Tests et Qualité
```bash
make tests             # Lance les tests PHPUnit
make cache-clear       # Vide le cache Symfony
make phpcs             # Corrige le style de code (PSR-12)
make phpstan           # Analyse statique du code
```

### Configuration Complète
```bash
make setup             # Configuration initiale complète
make install           # Installation complète (composer + setup)
```

## ⚙️ Configuration

### Variables d'Environnement

Les variables sont définies dans `compose.yml` :

```yaml
# Mailer (pour les emails)
MAILER_DSN: smtp://mailer:1025
MAILER_TRANSPORT: smtp
MAILER_HOST: mailer
MAILER_PORT: 1025
```

## 🧪 Tests et Développement

### Lancer les Tests
```bash
# Tests complets
make tests

# Tests avec coverage (dans le container)
make sh
php bin/phpunit --coverage-html var/coverage
```

### Debugging
```bash
# Accéder au container PHP
make sh

# Voir les logs Symfony
tail -f var/log/dev.log

# Logs Docker
make logs
```

### Analyse de Code
```bash
# Analyse statique complète
make phpstan

# Correction automatique du style
make phpcs

# Cache clearing pour les changements de config
make cache-clear
```

## 📦 Structure du Projet

```
src/
├── Controller/        # Contrôleurs Symfony
├── Entity/           # Entités Doctrine
├── Repository/       # Repositories Doctrine
├── Service/          # Services métier
└── ...

config/               # Configuration Symfony
public/               # Point d'entrée web
var/                  # Cache, logs, sessions
vendor/               # Dépendances Composer
```

## 🔧 Développement Local

### Premier Démarrage
```bash
# 1. Cloner et se placer dans le dossier
git clone <repo-url> && cd mtx-backend

# 2. Démarrer l'infrastructure
make up

# 3. Installation complète
make install

# 4. Vérifier que tout fonctionne
curl http://localhost:8080
```

### Workflow de Développement
```bash
# Démarrer la journée
make up

# Après modification de code
make cache-clear
make tests

# Avant commit
make phpstan
make phpcs

# Fin de journée
make down
```

## 🚀 Production

### Build Optimisé
```bash
# Build sans cache pour production
make build

# Ou avec Docker directement
docker-compose build --no-cache --pull
```

### Variables de Production
Créer un fichier `.env.prod` avec :
```bash
APP_ENV=prod
APP_DEBUG=false
# Autres variables spécifiques à votre environnement de production
```

# Tests des API avec curl

Cette section présente les commandes curl pour tester les différents endpoints de l'API de prêts.

## Configuration de l'environnement

Avant de lancer le build ou de démarrer l'application, veuillez créer un fichier `.env` à la racine de votre projet avec les variables d'environnement suivantes :

Ceci est un exemple,

```env
APP_ENV=dev


# In all environments, the following files are loaded if they exist,
# the latter taking precedence over the former:
#
#  * .env                contains default values for the environment variables needed by the app
#  * .env.local          uncommitted file with local overrides
#  * .env.$APP_ENV       committed environment-specific defaults
#  * .env.$APP_ENV.local uncommitted environment-specific overrides
#
# Real environment variables win over .env files.
#
# DO NOT DEFINE PRODUCTION SECRETS IN THIS FILE NOR IN ANY OTHER COMMITTED FILES.
# https://symfony.com/doc/current/configuration/secrets.html
#
# Run "composer dump-env prod" to compile .env files for production use (requires symfony/flex >=1.2).
# https://symfony.com/doc/current/best_practices.html#use-environment-variables-for-infrastructure-configuration

###> symfony/framework-bundle ###
APP_SECRET=6d2b931ba735af183faf08e84bc6e982
###< symfony/framework-bundle ###
###> doctrine/doctrine-bundle ###
# Format described at https://www.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# IMPORTANT: You MUST configure your server version, either here or in config/packages/doctrine.yaml
#
# DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=8.0.32&charset=utf8mb4"
# DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
DATABASE_URL="mysql://mtx_admin:mtx@database:3306/mtx_database?serverVersion=8.0&charset=utf8mb4"

#DATABASE_URL="mysql://root:root@127.0.0.1:3306/mtx_database?serverVersion=8.0&charset=utf8mb4"

###< doctrine/doctrine-bundle ###

###> symfony/mailer ###
MAILER_DSN=smtp://mtx_mail:1025
MAILER_FROM_ADDRESS='admin@example.com'
###< symfony/mailer ###

###> nelmio/cors-bundle ###
CORS_ALLOW_ORIGIN='*'
###< nelmio/cors-bundle ###

OTP_EXPIRATION_MINUTES=15
# The number of OTPs that can be generated for a single user within the specified interval.
JWT_TOKEN_TTL=1000000000


# The maximum number of login attempts allowed within the specified interval.
LOGIN_RATE_LIMIT=5

# The time interval within which the maximum number of login attempts is allowed.
# This setting is used to implement a login rate limiting mechanism to prevent
# brute-force attacks. The value should be a valid time interval string, e.g.
# "1 minute", "30 seconds", etc.
LOGIN_RATE_INTERVAL="1 minute"

###> symfony/lock ###
# Choose one of the stores below
# postgresql+advisory://db_user:db_password@localhost/db_name
LOCK_DSN=flock
###< symfony/lock ###

FRONTEND_URL=http://localhost:8081

###> symfony/mailer ###
mailer_from_address='noreply@mtx.com'
MAILER_FROM_NAME='mtx Madagascar'
###< symfony/mailer ###
#MAILER_DSN=smtp://mailer:1025
MAILER_DSN=smtp://localhost:1025


###> cybersource/config ###
CYBERSOURCE_MERCHANT_ID=1111100109
CYBERSOURCE_KEY_ID=ba393c18-cca2-456e-95f4-dfec478d307c
CYBERSOURCE_SECRET_KEY=eeMD+rC3Efhkb4uN4Lhkeir6n9Pxg07EQPPZCzwfQAI=
CYBERSOURCE_ENV=production

# ou 'production'
###< cybersource/config ###

###> app/encryption ###
ENCRYPTION_KEY=Swu89PiqMUZlKYNmNzpVOW7wnC+sv8iJVUbhBm9KQT4=
###< app/encryption ###
CYBERSOURCE_WEBHOOK_SECRET=2f7afd00-4911-95e6-e063-a0588e0aa4cf


LINK_EXPIRATION_MINUTES=15
```

**Important :** Assurez-vous de remplacer toutes les valeurs par vos propres configurations avant de lancer l'application.

## Prérequis

- Assurez-vous que votre serveur API est démarré et accessible sur `localhost:8080`
- curl doit être installé sur votre système
- Le fichier `.env` doit être configuré (voir section ci-dessus)

## Endpoints disponibles

### Récupération des taux de prêts (GET)

Cette endpoint permet de récupérer les taux de prêts avec pagination et filtres.

**Commande curl :**

```bash
curl -X GET \
  "localhost:8080/api/loan_rates?page=1&itemsPerPage=1&amount=50000&duration=20" \
  -H "Content-Type: application/json"
```

**Paramètres de requête :**
- `page` : Numéro de la page (pagination)
- `itemsPerPage` : Nombre d'éléments par page
- `amount` : Montant du prêt souhaité
- `duration` : Durée du prêt en années

### Comparaison de prêts (POST)

Cette endpoint permet de comparer différentes offres de prêts en envoyant les informations du demandeur.

**Commande curl :**

```bash
curl -X POST \
  "localhost:8080/api/loans/compare" \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 100000,
    "duration": 20,
    "name": "Joe Doe",
    "email": "test@test.com",
    "phone": "0612345678"
  }'
```

**Corps de la requête (JSON) :**
- `amount` : Montant du prêt demandé
- `duration` : Durée du prêt en années
- `name` : Nom complet du demandeur
- `email` : Adresse email du demandeur
- `phone` : Numéro de téléphone du demandeur

## Réponses attendues

Les API retournent des réponses au format JSON. Vérifiez que :
- Le code de statut HTTP est 200 pour une requête réussie
- La réponse contient les données attendues au format JSON
- Les headers de réponse incluent `Content-Type: application/json`

## Exemples de tests

Pour tester rapidement vos API, vous pouvez copier-coller les commandes ci-dessus dans votre terminal. Assurez-vous que votre serveur est bien démarré avant d'exécuter les commandes.

### Test complet

```bash
# Test de l'endpoint GET
echo "Test de récupération des taux de prêts..."
curl -X GET \
  "localhost:8080/api/loan_rates?page=1&itemsPerPage=1&amount=50000&duration=20" \
  -H "Content-Type: application/json"

echo -e "\n\n"

# Test de l'endpoint POST
echo "Test de comparaison de prêts..."
curl -X POST \
  "localhost:8080/api/loans/compare" \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 100000,
    "duration": 20,
    "name": "Joe Doe",
    "email": "test@test.com",
    "phone": "0612345678"
  }'
```

## 🤝 Contribution

1. **Fork** le projet
2. Créer une branche (`git checkout -b feature/nouvelle-fonctionnalite`)
3. **Tester** le code (`make tests && make phpstan`)
4. **Commit** (`git commit -m 'Ajout nouvelle fonctionnalité'`)
5. **Push** (`git push origin feature/nouvelle-fonctionnalite`)
6. Créer une **Pull Request**

## 📞 Support

- **Issues** : Ouvrir un ticket sur GitHub
- **Documentation** : Consulter la doc Symfony officielle
- **Docker** : [Documentation Docker Compose](https://docs.docker.com/compose/)

---

## 🎯 Points Techniques Clés

- **PHP 8.2** avec toutes les extensions nécessaires
- **Architecture containerisée** pour la portabilité
- **Makefile** pour simplifier les opérations courantes
- **Standards PSR-12** avec validation automatique
- **Analyse statique** niveau élevé avec PHPStan
- **Tests unitaires** et fonctionnels intégrés
- **Hot reload** pour le développement local


## 💡 Prochaines Améliorations

- [ ] Ajout de Redis pour le cache
- [ ] Configuration CI/CD avec GitHub Actions
- [ ] Monitoring avec Prometheus/Grafana
- [ ] Tests E2E avec Panther
- [ ] Documentation API avec API Platform
- [ ] Profiling avec Blackfire
