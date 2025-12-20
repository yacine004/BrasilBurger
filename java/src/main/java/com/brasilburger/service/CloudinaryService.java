package com.brasilburger.service;

import com.brasilburger.config.CloudinaryConfig;
import com.cloudinary.Cloudinary;
import com.cloudinary.utils.ObjectUtils;

import java.io.File;
import java.io.IOException;
import java.util.Map;

public class CloudinaryService {
    
    private final Cloudinary cloudinary;
    
    public CloudinaryService() {
        this.cloudinary = CloudinaryConfig.getCloudinary();
    }
    
    /**
     * Upload une image vers Cloudinary
     * @param filePath Chemin absolu vers l'image
     * @param folder Dossier dans Cloudinary (ex: "burgers", "menus", "complements")
     * @return URL securisee de l'image uploadee
     */
    public String uploadImage(String filePath, String folder) throws IOException {
        File file = new File(filePath);
        
        if (!file.exists()) {
            throw new IOException("Fichier non trouve : " + filePath);
        }
        
        System.out.println("[^] Upload en cours : " + file.getName() + "...");
        
        @SuppressWarnings("unchecked")
        Map<String, Object> uploadResult = cloudinary.uploader().upload(file, ObjectUtils.asMap(
            "folder", folder,
            "transformation", ObjectUtils.asMap(
                "width", 800,
                "height", 600,
                "crop", "limit"
            )
        ));
        
        String secureUrl = (String) uploadResult.get("secure_url");
        System.out.println("[OK] Image uploadee : " + secureUrl);
        
        return secureUrl;
    }
    
    /**
     * Supprime une image de Cloudinary
     * @param imageUrl URL de l'image Cloudinary
     * @return true si suppression reussie
     */
    public boolean deleteImage(String imageUrl) throws IOException {
        String publicId = extractPublicId(imageUrl);
        if (publicId == null) {
            return false;
        }
        
        @SuppressWarnings("unchecked")
        Map<String, Object> result = cloudinary.uploader().destroy(publicId, ObjectUtils.emptyMap());
        return "ok".equals(result.get("result"));
    }
    
    /**
     * Extrait le public_id d'une URL Cloudinary
     */
    private String extractPublicId(String imageUrl) {
        if (imageUrl == null || !imageUrl.contains("cloudinary.com")) {
            return null;
        }
        
        String[] parts = imageUrl.split("/upload/");
        if (parts.length < 2) {
            return null;
        }
        
        String path = parts[1];
        int lastSlash = path.lastIndexOf('/');
        int lastDot = path.lastIndexOf('.');
        
        if (lastSlash != -1 && lastDot != -1) {
            return path.substring(lastSlash + 1, lastDot);
        }
        
        return null;
    }
}
