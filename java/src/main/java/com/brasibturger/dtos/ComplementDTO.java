package com.brasibturger.dtos;

import lombok.*;
import jakarta.validation.constraints.*;
import java.math.BigDecimal;

@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class ComplementDTO {
    private Long idComplement;
    
    @NotBlank(message = "Le nom du complément est requis")
    @Size(min = 3, max = 150)
    private String nom;
    
    @Size(max = 1000)
    private String description;
    
    @NotNull(message = "Le prix est requis")
    @DecimalMin(value = "0.01", message = "Le prix doit être supérieur à 0")
    private BigDecimal prix;
    
    @Size(max = 500)
    private String image;
    
    @NotBlank(message = "Le type de complément est requis")
    private String typeComplement;
    
    @NotBlank(message = "Le statut est requis")
    private String statut;
}
