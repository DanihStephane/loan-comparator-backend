# MTX Backend API

Application backend développée avec Symfony et conteneurisée avec Docker pour un déploiement et un développement simplifiés.

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
cd mtx-backend

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

![Capture d’écran du 2025-06-11 19-32-56](https://github.com/user-attachments/assets/42f8eb4e-886a-4cc7-a3ed-6d13c87a1504)


## 💡 Prochaines Améliorations

- [ ] Ajout de Redis pour le cache
- [ ] Configuration CI/CD avec GitHub Actions
- [ ] Monitoring avec Prometheus/Grafana
- [ ] Tests E2E avec Panther
- [ ] Documentation API avec API Platform
- [ ] Profiling avec Blackfire
