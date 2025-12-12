package com.brasibturger.dtos;

import lombok.*;
import jakarta.validation.constraints.*;
import java.math.BigDecimal;

@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class CommandeDTO {
    private Long idCommande;
    
    @NotNull(message = "L'ID client est requis")
    private Long idClient;
    
    @NotNull(message = "Le montant total est requis")
    @DecimalMin(value = "0.01")
    private BigDecimal montantTotal;
    
    @NotBlank(message = "L'état est requis")
    private String etat;
    
    @NotBlank(message = "Le type de livraison est requis")
    private String typeLivraison;
    
    private Long idZone;
    private Long idLivreur;
    private String notes;
}
