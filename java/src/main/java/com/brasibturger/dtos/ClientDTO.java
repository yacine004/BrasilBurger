package com.brasibturger.dtos;

import lombok.*;
import jakarta.validation.constraints.*;

@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class ClientDTO {
    private Long idClient;
    
    @NotBlank(message = "Le nom est requis")
    @Size(min = 2, max = 100)
    private String nom;
    
    @NotBlank(message = "Le prénom est requis")
    @Size(min = 2, max = 100)
    private String prenom;
    
    @NotBlank(message = "L'email est requis")
    @Email(message = "L'email doit être valide")
    private String email;
    
    @NotBlank(message = "Le téléphone est requis")
    @Size(min = 9, max = 20)
    private String telephone;
    
    @Size(max = 500)
    private String adresse;
    
    private String statut;
}
