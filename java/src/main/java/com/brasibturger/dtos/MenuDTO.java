package com.brasibturger.dtos;

import lombok.*;
import jakarta.validation.constraints.*;
import java.math.BigDecimal;

@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class MenuDTO {
    private Long idMenu;
    
    @NotBlank(message = "Le nom du menu est requis")
    @Size(min = 3, max = 150)
    private String nom;
    
    @Size(max = 1000)
    private String description;
    
    @Size(max = 500)
    private String image;
    
    @NotBlank(message = "Le statut est requis")
    private String statut;
}
