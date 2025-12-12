package com.brasibturger.services;

import com.brasibturger.dtos.MenuDTO;
import com.brasibturger.exceptions.ApiException;
import com.brasibturger.models.Menu;
import com.brasibturger.repositories.MenuRepository;
import lombok.RequiredArgsConstructor;
import lombok.extern.slf4j.Slf4j;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;
import java.util.stream.Collectors;

@Service
@RequiredArgsConstructor
@Transactional
@Slf4j
public class MenuService {
    
    private final MenuRepository menuRepository;
    
    public List<MenuDTO> getAllMenus() {
        log.info("Retrieving all menus");
        return menuRepository.findAll().stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public List<MenuDTO> getActiveMenus() {
        log.info("Retrieving active menus");
        return menuRepository.findByStatut(Menu.Statut.ACTIF.toString()).stream()
                .map(this::convertToDTO)
                .collect(Collectors.toList());
    }
    
    public MenuDTO getMenuById(Long id) {
        log.info("Retrieving menu with id: {}", id);
        Menu menu = menuRepository.findById(id)
                .orElseThrow(() -> new ApiException("Menu not found with id: " + id, 404));
        return convertToDTO(menu);
    }
    
    public MenuDTO searchMenuByName(String name) {
        log.info("Searching menu by name: {}", name);
        List<Menu> menus = menuRepository.findByNomContainingIgnoreCase(name);
        if (menus.isEmpty()) {
            throw new ApiException("Menu not found with name containing: " + name, 404);
        }
        return convertToDTO(menus.get(0));
    }
    
    public MenuDTO createMenu(MenuDTO menuDTO) {
        log.info("Creating new menu: {}", menuDTO.getNom());
        Menu menu = convertToEntity(menuDTO);
        menu.setStatut(Menu.Statut.ACTIF);
        Menu saved = menuRepository.save(menu);
        return convertToDTO(saved);
    }
    
    public MenuDTO updateMenu(Long id, MenuDTO menuDTO) {
        log.info("Updating menu with id: {}", id);
        Menu menu = menuRepository.findById(id)
                .orElseThrow(() -> new ApiException("Menu not found with id: " + id, 404));
        
        menu.setNom(menuDTO.getNom());
        menu.setDescription(menuDTO.getDescription());
        menu.setImage(menuDTO.getImage());
        menu.setStatut(Menu.Statut.valueOf(menuDTO.getStatut()));
        
        Menu updated = menuRepository.save(menu);
        return convertToDTO(updated);
    }
    
    public void deleteMenu(Long id) {
        log.info("Deleting menu with id: {}", id);
        if (!menuRepository.existsById(id)) {
            throw new ApiException("Menu not found with id: " + id, 404);
        }
        menuRepository.deleteById(id);
    }
    
    private MenuDTO convertToDTO(Menu menu) {
        return MenuDTO.builder()
                .nom(menu.getNom())
                .description(menu.getDescription())
                .image(menu.getImage())
                .statut(menu.getStatut() != null ? menu.getStatut().toString() : null)
                .build();
    }
    
    private Menu convertToEntity(MenuDTO menuDTO) {
        return Menu.builder()
                .nom(menuDTO.getNom())
                .description(menuDTO.getDescription())
                .image(menuDTO.getImage())
                .statut(Menu.Statut.valueOf(menuDTO.getStatut()))
                .build();
    }
}
