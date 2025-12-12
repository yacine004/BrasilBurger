package com.brasibturger.services;

import com.cloudinary.Cloudinary;
import com.cloudinary.utils.ObjectUtils;
import lombok.extern.slf4j.Slf4j;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.stereotype.Service;
import org.springframework.web.multipart.MultipartFile;

import java.io.IOException;
import java.util.Map;

@Service
@Slf4j
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
        
        log.info("Cloudinary configuré avec cloud_name: {}", cloudName);
    }
    
    /**
     * Upload une image vers Cloudinary
     * @param file Fichier multipart à uploader
     * @param folder Dossier Cloudinary (ex: "burgers", "menus", "complements")
     * @return URL sécurisée de l'image uploadée
     */
    public String uploadImage(MultipartFile file, String folder) throws IOException {
        if (file == null || file.isEmpty()) {
            throw new IllegalArgumentException("Le fichier est vide");
        }
        
        log.info("Upload de l'image {} dans le dossier {}", file.getOriginalFilename(), folder);
        
        Map uploadResult = cloudinary.uploader().upload(file.getBytes(),
            ObjectUtils.asMap(
                "folder", folder,
                "resource_type", "auto",
                "transformation", ObjectUtils.asMap(
                    "width", 800,
                    "height", 600,
                    "crop", "limit"
                )
            ));
        
        String secureUrl = (String) uploadResult.get("secure_url");
        log.info("Image uploadée avec succès: {}", secureUrl);
        
        return secureUrl;
    }
    
    /**
     * Supprime une image de Cloudinary
     * @param publicId ID public de l'image (ex: "burgers/burger_xyz")
     * @return true si suppression réussie
     */
    public boolean deleteImage(String publicId) throws IOException {
        if (publicId == null || publicId.isEmpty()) {
            return false;
        }
        
        log.info("Suppression de l'image: {}", publicId);
        
        Map result = cloudinary.uploader().destroy(publicId, ObjectUtils.emptyMap());
        String resultStatus = (String) result.get("result");
        
        boolean success = "ok".equals(resultStatus);
        log.info("Image {} : {}", publicId, success ? "supprimée" : "non trouvée");
        
        return success;
    }
    
    /**
     * Extrait le public_id depuis une URL Cloudinary
     * Ex: https://res.cloudinary.com/demo/image/upload/v1234/burgers/burger1.jpg
     * -> burgers/burger1
     */
    public String extractPublicId(String imageUrl) {
        if (imageUrl == null || !imageUrl.contains("cloudinary.com")) {
            return null;
        }
        
        try {
            // Format: .../upload/v1234567/folder/image.jpg
            String[] parts = imageUrl.split("/upload/");
            if (parts.length < 2) return null;
            
            String afterUpload = parts[1];
            // Enlever la version (v1234567/)
            String withoutVersion = afterUpload.substring(afterUpload.indexOf('/') + 1);
            // Enlever l'extension
            return withoutVersion.substring(0, withoutVersion.lastIndexOf('.'));
        } catch (Exception e) {
            log.warn("Impossible d'extraire publicId de: {}", imageUrl);
            return null;
        }
    }
}
