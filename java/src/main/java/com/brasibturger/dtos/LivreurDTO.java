package com.brasibturger.dtos;

import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.Size;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@Builder
@NoArgsConstructor
@AllArgsConstructor
public class LivreurDTO {
    
    @NotBlank(message = "Livreur name is required")
    private String nom;
    
    @NotBlank(message = "Livreur surname is required")
    private String prenom;
    
    @NotBlank(message = "Phone number is required")
    @Size(min = 9, max = 20, message = "Phone number must be between 9 and 20 characters")
    private String telephone;
    
    @NotNull(message = "Zone is required")
    private Long idZone;
    
    @Builder.Default
    private String statut = "ACTIF";
}
