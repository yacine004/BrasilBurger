package com.brasibturger.dtos;

import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.DecimalMin;
import lombok.AllArgsConstructor;
import lombok.Builder;
import lombok.Data;
import lombok.NoArgsConstructor;

import java.math.BigDecimal;

@Data
@Builder
@NoArgsConstructor
@AllArgsConstructor
public class ZoneDTO {
    
    @NotBlank(message = "Zone name is required")
    private String nomZone;
    
    @NotNull(message = "Delivery price is required")
    @DecimalMin(value = "0.01", message = "Delivery price must be greater than 0")
    private BigDecimal prixLivraison;
    
    private String description;
}
