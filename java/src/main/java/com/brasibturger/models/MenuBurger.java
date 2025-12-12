package com.brasibturger.models;

import lombok.*;
import jakarta.persistence.*;

@Entity
@Table(name = "menu_burger")
@Data
@NoArgsConstructor
@AllArgsConstructor
@Builder
public class MenuBurger {
    
    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long idMenuBurger;
    
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "id_menu", nullable = false)
    private Menu menu;
    
    @ManyToOne(fetch = FetchType.LAZY)
    @JoinColumn(name = "id_burger", nullable = false)
    private Burger burger;
}
