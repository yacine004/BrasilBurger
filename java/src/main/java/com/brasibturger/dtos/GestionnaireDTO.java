package com.brasibturger.dtos;

import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.Size;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

@Data
@Builder
@NoArgsConstructor
@AllArgsConstructor
public class GestionnaireDTO {
    
    @NotBlank(message = "Name is required")
    private String nom;
    
    @NotBlank(message = "Surname is required")
    private String prenom;
    
    @NotBlank(message = "Email is required")
    @Email(message = "Email should be valid")
    private String email;
    
    @NotBlank(message = "Password is required")
    @Size(min = 6, message = "Password must be at least 6 characters")
    private String motDePasse;
    
    @NotBlank(message = "Phone number is required")
    @Size(min = 9, max = 20, message = "Phone number must be between 9 and 20 characters")
    private String telephone;
    
    @Builder.Default
    private String statut = "ACTIF";
}
