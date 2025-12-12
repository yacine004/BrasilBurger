package com.brasibturger.models;

import lombok.*;
import jakarta.persistence.*;

@Entity
@Table(name = "menu_complement")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class MenuComplement {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long idMenuComplement;
    
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "id_menu", nullable = false)
    private Menu menu;
    
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "id_complement", nullable = false)
    private Complement complement;
}
