package com.brasibturger.services;

import com.brasibturger.dtos.BurgerDTO;
import com.brasibturger.models.Burger;
import com.brasibturger.repositories.BurgerRepository;
import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;
import java.util.List;
import java.util.stream.Collectors;

@Service
@RequiredArgsConstructor
@Transactional
public class BurgerService {
    
    private final BurgerRepository burgerRepository;
    
    public List<BurgerDTO> getAllBurgers() {
        return burgerRepository.findAll()
                .stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public List<BurgerDTO> getActiveBurgers() {
        return burgerRepository.findByStatut("ACTIF")
                .stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public BurgerDTO getBurgerById(Long id) {
        return burgerRepository.findById(id)
                .map(this::convertToDTO)
                .orElseThrow(() -> new RuntimeException("Burger non trouvé"));
    }
    
    public BurgerDTO createBurger(BurgerDTO burgerDTO) {
        Burger burger = convertToEntity(burgerDTO);
        burger.setStatut(Burger.Statut.ACTIF);
        Burger saved = burgerRepository.save(burger);
        return convertToDTO(saved);
    }
    
    public BurgerDTO updateBurger(Long id, BurgerDTO burgerDTO) {
        Burger burger = burgerRepository.findById(id)
                .orElseThrow(() -> new RuntimeException("Burger non trouvé"));
        
        burger.setNom(burgerDTO.getNom());
        burger.setDescription(burgerDTO.getDescription());
        burger.setPrix(burgerDTO.getPrix());
        burger.setImage(burgerDTO.getImage());
        
        Burger updated = burgerRepository.save(burger);
        return convertToDTO(updated);
    }
    
    public void deleteBurger(Long id) {
        burgerRepository.deleteById(id);
    }
    
    private BurgerDTO convertToDTO(Burger burger) {
        return BurgerDTO.builder()
                .idBurger(burger.getIdBurger())
                .nom(burger.getNom())
                .description(burger.getDescription())
                .prix(burger.getPrix())
                .image(burger.getImage())
                .statut(burger.getStatut().toString())
                .build();
    }
    
    private Burger convertToEntity(BurgerDTO dto) {
        return Burger.builder()
                .nom(dto.getNom())
                .description(dto.getDescription())
                .prix(dto.getPrix())
                .image(dto.getImage())
                .build();
    }
}
