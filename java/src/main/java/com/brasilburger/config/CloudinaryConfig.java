package com.brasilburger.config;

import com.cloudinary.Cloudinary;
import com.cloudinary.utils.ObjectUtils;

public class CloudinaryConfig {
    
    private static Cloudinary cloudinary;
    
    private static final String CLOUD_NAME = "dd8kegetk";
    private static final String API_KEY = "427329567874199";
    private static final String API_SECRET = "27B9-zdx3cUBKSnIHYCxNWiH96s";
    
    public static Cloudinary getCloudinary() {
        if (cloudinary == null) {
            cloudinary = new Cloudinary(ObjectUtils.asMap(
                "cloud_name", CLOUD_NAME,
                "api_key", API_KEY,
                "api_secret", API_SECRET
            ));
            System.out.println("[OK] Cloudinary configure.");
        }
        return cloudinary;
    }
}
