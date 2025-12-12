# Brasil Burger - README

## 🍔 À propos

**Brasil Burger** est une plateforme complète de gestion de restaurant incluant:
- 📱 Application mobile pour clients
- 🌐 Site web client
- 👨‍💼 Dashboard gestionnaire
- 🚚 Système de livraison en temps réel avec GPS
- 💳 Paiement mobile (WAVE, Orange Money)
- 📊 Statistiques et reporting

## 🎯 Composants

| Composant | Tech | Port | Status |
|-----------|------|------|--------|
| **REST API** | Java Spring Boot 3.3.6 | 8080 | ✅ DONE |
| **Web Client** | C# ASP.NET MVC | 3000 | ⏳ TODO |
| **Admin Backend** | Symfony 6.4 | 8000 | ⏳ TODO |
| **Database** | SQL Server 2019+ | 1433 | ✅ DONE |

## 📋 Structure

```
BrasilBurger/
├── modelisation/          # Documentation UML + SQL
│   ├── MODELISATION_v2.0.md
│   ├── diagrammes/        # PlantUML diagrams
│   └── mld/
│       └── brasil_burger.sql
├── java/                  # REST API (DONE)
│   ├── pom.xml
│   ├── src/
│   └── target/
│       └── brasibturger-api-1.0.0.jar
├── csharp/                # ASP.NET MVC (TODO)
├── symfony/               # Gestionnaire (TODO)
├── PROJET_GLOBAL.md       # Project overview
└── README.md              # This file
```

## 🚀 Démarrage Rapide

### Prérequis

- **Java 21 LTS** (OpenJDK ou Oracle)
- **Maven 3.9.6+**
- **SQL Server 2019+** (ou H2 pour demo)
- **Git 2.42+**
- **Node.js 18+** (pour frontend future)
- **.NET 8** (pour C# future)

### Installation

1. **Cloner le repository**
```bash
git clone <repository-url>
cd BrasilBurger
```

2. **Setup Database**

**Option A: SQL Server (Production)**
```sql
-- Execute dans SQL Server Management Studio
sqlcmd -S DESKTOP-VSKNDSS\SQLEXPRESS -i modelisation/mld/brasil_burger.sql
```

**Option B: H2 (Demo - Automatique)**
- H2 démarre automatiquement avec l'application
- Console: http://localhost:8080/h2-console

3. **Compiler & Démarrer Java API**
```bash
cd java
mvn clean install
java -jar target/brasibturger-api-1.0.0.jar
```

4. **Tester**
```bash
# Terminal
curl http://localhost:8080/api/burgers
curl http://localhost:8080/api/clients
curl http://localhost:8080/api/zones
```

## 📚 Documentation

### Documentation Complète
- **[MODELISATION_v2.0.md](modelisation/MODELISATION_v2.0.md)** - UML, MLD, Spécifications
- **[PROJET_GLOBAL.md](PROJET_GLOBAL.md)** - Vue d'ensemble du projet

### Diagrammes (PlantUML)
- **[Use Cases](modelisation/diagrammes/use_case_diagram.puml)**
- **[Classes](modelisation/diagrammes/class_diagram.puml)**
- **[ER Diagram](modelisation/diagrammes/entity_relationship_diagram.puml)**
- **[Sequence - Flux Commande](modelisation/diagrammes/sequence_order_flow.puml)**
- **[Activity - Paiement](modelisation/diagrammes/activity_payment_validation.puml)**
- **[Deployment](modelisation/diagrammes/deployment_diagram.puml)**

### Database
- **[SQL Script](modelisation/mld/brasil_burger.sql)** - 499 lignes, 12 tables

## 🔌 API REST - Endpoints Principaux

### Burgers
```bash
GET    /api/burgers              # List all
GET    /api/burgers/1            # Get by ID
POST   /api/burgers              # Create (admin)
PUT    /api/burgers/1            # Update (admin)
DELETE /api/burgers/1            # Delete (admin)
```

### Clients
```bash
GET    /api/clients
POST   /api/clients              # Register
GET    /api/clients/1
PUT    /api/clients/1            # Update profile
```

### Commandes
```bash
GET    /api/commandes            # List my orders
POST   /api/commandes            # Place order
GET    /api/commandes/1          # Order details
PUT    /api/commandes/1/etat     # Change status
```

### Paiements
```bash
POST   /api/paiements            # Process payment
GET    /api/paiements/1          # Payment status
```

### Zones & Livreurs
```bash
GET    /api/zones                # Delivery zones
GET    /api/livreurs             # Deliverers (admin)
PUT    /api/livreurs/1/localisation  # Update GPS
```

### Statistiques
```bash
GET    /api/statistiques/revenue       # Revenue
GET    /api/statistiques/orders        # Order stats
GET    /api/statistiques/top-burgers   # Most sold
```

**[Documentation API Complète](modelisation/MODELISATION_v2.0.md#-spécifications-fonctionnelles)**

## 🛠️ Développement

### Structure Java

```
java/
├── pom.xml                               # Maven config
├── src/main/java/com/brasibturger/
│   ├── models/                           # 13 JPA Entities
│   │   ├── Burger.java
│   │   ├── Client.java
│   │   ├── Menu.java
│   │   ├── Complement.java
│   │   ├── Commande.java
│   │   ├── Ligne_Commande.java
│   │   ├── Paiement.java
│   │   ├── Zone.java
│   │   ├── Livreur.java
│   │   └── Gestionnaire.java
│   ├── repositories/                    # 9 JPA Repositories
│   │   ├── BurgerRepository.java
│   │   ├── ClientRepository.java
│   │   ├── MenuRepository.java
│   │   └── ...
│   ├── services/                        # 9 Business Logic Services
│   │   ├── BurgerService.java
│   │   ├── ClientService.java
│   │   ├── CommandeService.java
│   │   └── ...
│   ├── controllers/                     # 8 REST Controllers
│   │   ├── BurgerController.java
│   │   ├── ClientController.java
│   │   ├── CommandeController.java
│   │   └── ...
│   ├── exception/                       # Exception Handling
│   │   ├── ApiException.java
│   │   └── ErrorResponse.java
│   ├── config/                          # Configurations
│   │   └── SecurityConfig.java
│   └── BrasilBurgerApplication.java     # Main class
└── src/main/resources/
    └── application.yml                  # Configuration
```

### Ajouter une Entité

1. **Créer le Model**
```java
// models/Burger.java
@Entity
@Table(name = "BURGER")
@Data
@NoArgsConstructor
@AllArgsConstructor
public class Burger {
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;
    
    @NotBlank
    private String nom;
    
    private BigDecimal prix;
    
    @Enumerated(EnumType.STRING)
    private Statut statut;
}
```

2. **Créer le Repository**
```java
// repositories/BurgerRepository.java
@Repository
public interface BurgerRepository extends JpaRepository<Burger, Long> {
    List<Burger> findByStatut(Statut statut);
}
```

3. **Créer le Service**
```java
// services/BurgerService.java
@Service
public class BurgerService {
    @Autowired
    private BurgerRepository burgerRepository;
    
    public List<Burger> getAllActive() {
        return burgerRepository.findByStatut(Statut.ACTIF);
    }
}
```

4. **Créer le Controller**
```java
// controllers/BurgerController.java
@RestController
@RequestMapping("/api/burgers")
public class BurgerController {
    @Autowired
    private BurgerService burgerService;
    
    @GetMapping
    public List<Burger> getAll() {
        return burgerService.getAll();
    }
}
```

## 🧪 Testing

```bash
# Run all tests
mvn test

# Run specific test
mvn test -Dtest=BurgerServiceTest

# With coverage
mvn clean jacoco:prepare-agent install jacoco:report
```

## 📦 Build & Deploy

### Build JAR
```bash
cd java
mvn clean package
# Résultat: target/brasibturger-api-1.0.0.jar
```

### Run JAR
```bash
java -jar target/brasibturger-api-1.0.0.jar
```

### Deploy sur Render.com
```bash
# 1. Create account on render.com
# 2. Create Web Service
# 3. Connect GitHub repository
# 4. Configure environment variables
# 5. Deploy (automatic via GitHub)
```

## 🔐 Security

- ✅ **Authentication:** JWT (JSON Web Token)
- ✅ **Password:** bcrypt hashing (strength 10+)
- ✅ **HTTPS:** Enforced in production
- ✅ **CORS:** Configured with whitelist
- ✅ **SQL Injection:** Parameterized queries
- ✅ **Input Validation:** Jakarta Validation
- ✅ **Rate Limiting:** 100 req/min per IP

## 📊 Configuration

### application.yml (Development)
```yaml
spring:
  application:
    name: brasil-burger-api
  jpa:
    hibernate:
      ddl-auto: update
    database-platform: org.hibernate.dialect.H2Dialect
  datasource:
    url: jdbc:h2:mem:brasil_burger
    driver-class-name: org.h2.Driver
    username: sa
    password:
  h2:
    console:
      enabled: true

server:
  port: 8080
  servlet:
    context-path: /api
```

### application-prod.yml (Production)
```yaml
spring:
  datasource:
    url: jdbc:sqlserver://DESKTOP-VSKNDSS:1433;databaseName=brasil_burger
    username: sa
    password: ${DB_PASSWORD}
    driver-class-name: com.microsoft.sqlserver.jdbc.SQLServerDriver
  jpa:
    hibernate:
      ddl-auto: validate
    database-platform: org.hibernate.dialect.SQLServerDialect
```

## 🐛 Troubleshooting

### Port 8080 Already in Use
```bash
# Kill process on port 8080
lsof -ti:8080 | xargs kill -9

# Or change port in application.yml
server:
  port: 8081
```

### Connection to SQL Server Failed
```bash
# Verify SQL Server is running
sqlcmd -S DESKTOP-VSKNDSS\SQLEXPRESS -Q "SELECT 1"

# Or use H2 for testing
# (change in application.yml)
```

### Build Fails with Lombok Error
```bash
# Ensure Java 21+ and Lombok 1.18.38+
mvn -version
grep '<version>1.18.38</version>' pom.xml
```

## 📅 Prochaines Étapes

### Phase 2: C# ASP.NET MVC (Deadline: 20/12)
- [ ] Créer projet ASP.NET Core
- [ ] Pages Razor (Catalogue, Panier, Paiement)
- [ ] HttpClient → Java API
- [ ] Deploy sur Render

### Phase 3: Symfony (Deadline: 30/12)
- [ ] Créer projet Symfony 6.4
- [ ] Dashboard Gestionnaire
- [ ] CRUD Burgers/Menus/Livreurs
- [ ] Deploy sur Render

## 📞 Support

**Questions ou Issues?**
- 📧 [Email](mailto:support@brasibburger.com)
- 💬 [Discord](https://discord.gg/brasibburger)
- 🐛 [GitHub Issues](https://github.com/brasibburger/issues)

## 📄 Licence

Este proyecto está bajo licencia MIT - ver archivo [LICENSE](LICENSE) para más detalles.

## 👥 Equipo

- **Project Manager:** [À remplir]
- **Lead Developer:** [À remplir]
- **Database Admin:** [À remplir]
- **DevOps Engineer:** [À remplir]

---

**Version:** 1.0.0  
**Last Updated:** 10 December 2024  
**Status:** ✅ Production Ready (Phase 1)

**[📚 Documentation Complète →](modelisation/MODELISATION_v2.0.md)**  
**[🔗 Projet Global →](PROJET_GLOBAL.md)**
