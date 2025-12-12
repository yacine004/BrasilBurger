package com.brasibturger.dtos;

import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.DecimalMin;
import jakarta.validation.constraints.NotBlank;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.math.BigDecimal;

@Data
@Builder
@NoArgsConstructor
@AllArgsConstructor
public class PaiementDTO {
    
    @NotNull(message = "Commande ID is required")
    private Long idCommande;
    
    @NotNull(message = "Payment amount is required")
    @DecimalMin(value = "0.01", message = "Payment amount must be greater than 0")
    private BigDecimal montant;
    
    @NotBlank(message = "Payment method is required")
    private String methode;
    
    @Builder.Default
    private String statut = "REUSSI";
    
    private String referencePaiement;
}
