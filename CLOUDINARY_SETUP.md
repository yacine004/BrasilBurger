# Configuration Cloudinary pour Brasil Burger

## Étape 1 : Créer compte Cloudinary

1. Allez sur https://cloudinary.com/users/register_free
2. Inscrivez-vous avec votre email
3. Confirmez votre email

## Étape 2 : Récupérer les credentials

Une fois connecté au Dashboard Cloudinary :
- **Cloud Name** : Visible en haut (ex: `dxxxxxx`)
- **API Key** : Sous "Product Environment Credentials"
- **API Secret** : Cliquez sur "Reveal" à côté de API Secret

## Étape 3 : Configurer Java Spring Boot

### Ajouter la dépendance dans `pom.xml` :

```xml
<dependency>
    <groupId>com.cloudinary</groupId>
    <artifactId>cloudinary-http44</artifactId>
    <version>1.39.0</version>
</dependency>
```

### Ajouter dans `application-dev.yml` (NON COMMITÉ) :

```yaml
cloudinary:
  cloud-name: YOUR_CLOUD_NAME
  api-key: YOUR_API_KEY
  api-secret: YOUR_API_SECRET
```

### Créer le service `CloudinaryService.java` :

```java
package com.brasibturger.services;

import com.cloudinary.Cloudinary;
import com.cloudinary.utils.ObjectUtils;
import lombok.RequiredArgsConstructor;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Service;
import org.springframework.web.multipart.MultipartFile;

import java.io.IOException;
import java.util.Map;

@Service
public class CloudinaryService {
    
    private final Cloudinary cloudinary;
    
    public CloudinaryService(
            @Value("${cloudinary.cloud-name}") String cloudName,
            @Value("${cloudinary.api-key}") String apiKey,
            @Value("${cloudinary.api-secret}") String apiSecret) {
        this.cloudinary = new Cloudinary(ObjectUtils.asMap(
            "cloud_name", cloudName,
            "api_key", apiKey,
            "api_secret", apiSecret
        ));
    }
    
    public String uploadImage(MultipartFile file, String folder) throws IOException {
        Map uploadResult = cloudinary.uploader().upload(file.getBytes(),
            ObjectUtils.asMap(
                "folder", folder,
                "resource_type", "auto"
            ));
        return (String) uploadResult.get("secure_url");
    }
    
    public void deleteImage(String publicId) throws IOException {
        cloudinary.uploader().destroy(publicId, ObjectUtils.emptyMap());
    }
}
```

### Créer l'endpoint d'upload dans `BurgerController.java` :

```java
@PostMapping("/{id}/image")
public ResponseEntity<BurgerDTO> uploadBurgerImage(
        @PathVariable Long id,
        @RequestParam("file") MultipartFile file) throws IOException {
    
    String imageUrl = cloudinaryService.uploadImage(file, "burgers");
    BurgerDTO updated = burgerService.updateBurgerImage(id, imageUrl);
    return ResponseEntity.ok(updated);
}
```

## Étape 4 : Tester l'upload

Avec Postman ou curl :

```bash
curl -X POST http://localhost:8080/api/burgers/91/image \
  -F "file=@burger.jpg"
```

## Étape 5 : Variables d'environnement Render

Quand vous déploierez sur Render, ajoutez ces variables :
- `CLOUDINARY_CLOUD_NAME`
- `CLOUDINARY_API_KEY`
- `CLOUDINARY_API_SECRET`
